<?php

namespace password;

require_once("src/password/Password.php");

/**
 * Uses info from: http://www.passwordmeter.com/
 */
class Analysis {

    /**
     * @var Password $analysed
     */
    private $analysed;

    public function __construct(Password $password) {
        $this->analysed = $password;
    }

    public function getPassword() {
        return $this->analysed;
    }

    public function countLowerCase() {
        $pattern = '/[a-zåäö]/u';
        preg_match_all($pattern, $this->analysed->getValue(), $matches);
        return count($matches[0]);
    }

    public function countUpperCase() {
        $pattern = '/[A-ZÅÄÖ]/u';
        preg_match_all($pattern, $this->analysed->getValue(), $matches);
        return count($matches[0]);
    }

    public function countNumbers() {
        $pattern = '/\d/u';
        preg_match_all($pattern, $this->analysed->getValue(), $matches);
        return count($matches[0]);
    }

    public function countSymbols() {
        return $this->analysed->countLetters() - $this->countNumbers() 
                                               - $this->countUpperCase() 
                                               - $this->countLowerCase();
    }

    public function countUnique() {
        $letters = array_filter(
            preg_split('//u', $this->analysed->getValue(), -1, PREG_SPLIT_NO_EMPTY)
        );

        $unique = array_count_values($letters);
        return count($unique);
    }

    public function countClasses() {
        $ret = 0;
        if ($this->countNumbers() > 0) $ret++;
        if ($this->countUpperCase() > 0) $ret++;
        if ($this->countLowerCase() > 0) $ret++;
        if ($this->countSymbols() > 0) $ret++;
        return $ret;
    }
}
