<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace AppBundle\Helper;


    class UtilsHelper
    {
        const LOWER_ALPHABET    = 'abcdefghjkmnpqrstuvwxyz';
        const UPPER_ALPHABET    = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        const DIGITS            = '0123456789';
        const TOKEN_LENGTH      = 15;

        /**
         * see http://snipplr.com/view/58585/generate-random-caracters-with-random-case-andor-random-digits/
         *
         * @example: XMu6cPEbqGCx1Yrdb4nDm4e0XxE84Egu5NfcBjRFs7QNm9ZBmFSPjmECeXme
         *
         * @param int  $length
         * @param bool $randomCase
         * @param bool $includeDigits
         * @return string
         * @throws \Exception
         */
        public static function generateToken(int $length         = self::TOKEN_LENGTH,
                                             bool $randomCase    = true,
                                             bool $includeDigits = true) : string
        {
            $lower  = self::LOWER_ALPHABET;
            $upper  = self::UPPER_ALPHABET;
            $digits = self::DIGITS;
            $chars  = $lower . ($randomCase ? $upper : '') . ($includeDigits ? $digits : '');
            $str    = '';

            $last_index = strlen($chars) - 1;

            for($i = 0; $i < $length; $i++) {
                mt_srand(hexdec(uniqid('', false)));
                $str .= $chars[random_int(0, $last_index)];
            }

            return $str;
        }

        /**
         * @param int $length
         * @return string
         * @throws \Exception
         */
        public static function generateRandomSpecialChar(int $length = 1) : string
        {
            $array  = ['$','_','.','+','!'];

            if ($length === 1) {
                return $array[random_int(0,4)];
            }

            $result = '';

            for ($i = 0; $i < $length; $i++) {
                $result .= $array[random_int(0,4)];
            }

            return $result;
        }

    }