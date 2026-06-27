<?php

namespace App\Support;

/**
 * TOTP (RFC 6238) — compatível com Google Authenticator / Authy.
 * SHA1, passo de 30s, 6 dígitos. Sem dependências externas.
 */
class Totp
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /** Gera um segredo aleatório em base32. */
    public static function secret(int $length = 16): string
    {
        $s = '';
        for ($i = 0; $i < $length; $i++) {
            $s .= self::ALPHABET[random_int(0, 31)];
        }
        return $s;
    }

    /** Verifica um código contra o segredo (janela de ±$window passos). */
    public static function verify(string $secret, string $code, int $window = 1): bool
    {
        $code = preg_replace('/\D/', '', $code);
        if (strlen($code) !== 6) {
            return false;
        }
        $counter = (int) floor(time() / 30);
        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals(self::codeAt($secret, $counter + $i), $code)) {
                return true;
            }
        }
        return false;
    }

    /** Código de 6 dígitos para um contador específico. */
    public static function codeAt(string $secret, int $counter): string
    {
        $key = self::base32Decode($secret);
        $bin = pack('N*', 0) . pack('N*', $counter); // contador big-endian 8 bytes
        $hash = hash_hmac('sha1', $bin, $key, true);
        $offset = ord($hash[19]) & 0xf;
        $val = ((ord($hash[$offset]) & 0x7f) << 24)
            | ((ord($hash[$offset + 1]) & 0xff) << 16)
            | ((ord($hash[$offset + 2]) & 0xff) << 8)
            | (ord($hash[$offset + 3]) & 0xff);

        return str_pad((string) ($val % 1000000), 6, '0', STR_PAD_LEFT);
    }

    /** URI otpauth:// para QR / cadastro manual no app autenticador. */
    public static function uri(string $secret, string $label, string $issuer): string
    {
        return 'otpauth://totp/' . rawurlencode($issuer . ':' . $label)
            . '?secret=' . $secret
            . '&issuer=' . rawurlencode($issuer)
            . '&algorithm=SHA1&digits=6&period=30';
    }

    private static function base32Decode(string $b32): string
    {
        $b32 = strtoupper($b32);
        $buffer = 0;
        $bits = 0;
        $out = '';
        foreach (str_split($b32) as $ch) {
            $pos = strpos(self::ALPHABET, $ch);
            if ($pos === false) {
                continue;
            }
            $buffer = ($buffer << 5) | $pos;
            $bits += 5;
            if ($bits >= 8) {
                $bits -= 8;
                $out .= chr(($buffer >> $bits) & 0xff);
            }
        }
        return $out;
    }
}
