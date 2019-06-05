<?php

class LuckyTickets
{
    const DEFAULT_FROM = 0;
    const DEFAULT_TO = 999999;

    public $from;
    public $to;
    public $sumToOneDigit = true;

    protected $emptyTemplate = [
        0 => 0, //for ticket 000000
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0,
        6 => 0,
        7 => 0,
        8 => 0,
        9 => 0,
    ];

    protected $emptyFullTemplate = [
        0 => 0, //for ticket 000000
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0,
        6 => 0,
        7 => 0,
        8 => 0,
        9 => 0,
        10 => 0,
        11 => 0,
        12 => 0,
        13 => 0,
        14 => 0,
        15 => 0,
        16 => 0,
        17 => 0,
        18 => 0,
        19 => 0,
        20 => 0,
        21 => 0,
        22 => 0,
        23 => 0,
        24 => 0,
        25 => 0,
        26 => 0,
        27 => 0,
    ];

    /**
     * LuckyTickets constructor.
     * @param int $from
     * @param int $to
     * @param bool $sumToOneDigit
     */
    public function __construct($from = self::DEFAULT_FROM, $to = self::DEFAULT_TO, $sumToOneDigit = true)
    {
        $this->from = isset($from) ? $from : self::DEFAULT_FROM;
        $this->to =  isset($to) ? $to : self::DEFAULT_FROM;
        $this->sumToOneDigit =  isset($sumToOneDigit) ? $sumToOneDigit : true;
        $this->_fullThousand = null;
    }

    public function validate()
    {
        $errors = [];
        if ($this->from < self::DEFAULT_FROM || $this->from > self::DEFAULT_TO) {
            $errors[] = 'Not valid FROM value';
        }
        if ($this->to < self::DEFAULT_FROM || $this->to > self::DEFAULT_TO) {
            $errors[] = 'Not valid TO value';
        }
        if ($this->from > $this->to) {
            $errors[] = 'FROM should be less TO';
        }
        return $errors;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $errors = $this->validate();
        if ($errors) {
            throw new Exception('Not valid input data. Use method `validate()` before `count()`');
        }
        $firstBegin = (int)($this->from / 1000);
        $firstEnd = (int)($this->to / 1000);
        $secondBegin = (int)($this->from % 1000);
        $secondEnd = (int)($this->to % 1000);

        $countNumbers = $this->getEmptyTemplate();


        if ($firstBegin < $firstEnd) {
            for ($num = $firstBegin + 1; $num <= $firstEnd - 1; $num++) {
                $firstSum = $this->getSum($num);
                $countNumbers[$firstSum] += $this->countFullThousand()[$firstSum];
            }
            $firstBeginSum = $this->getSum($firstBegin);
            for ($num = $secondBegin; $num <= 999; $num++) {
                if ($firstBeginSum == $this->getSum($num)) {
                    $countNumbers[$firstBeginSum]++;
                }
            }
            $firstEndSum = $this->getSum($firstEnd);
            for ($num = 0; $num <= $secondEnd; $num++) {
                if ($firstEndSum == $this->getSum($num)) {
                    $countNumbers[$firstEndSum]++;
                }
            }
        } else {
            $firstBeginSum = $this->getSum($firstBegin);
            for ($num = $secondBegin; $num <= $secondEnd; $num++) {
                if ($firstBeginSum == $this->getSum($num)) {
                    $countNumbers[$firstBeginSum]++;
                }
            }
        }

        return array_sum($countNumbers);
    }

    /**
     * @return array
     */
    protected function getEmptyTemplate()
    {
        return $this->sumToOneDigit ? $this->emptyTemplate : $this->emptyFullTemplate;
    }

    protected $_fullThousand = null;
    /**
     * @return array
     */
    protected function countFullThousand()
    {
        if ($this->_fullThousand === null) {
            $this->_fullThousand = $this->getEmptyTemplate();
            for ($num = 0; $num <= 999; $num++) {
                $this->_fullThousand[$this->getSum($num)]++;
            }
        }
        return $this->_fullThousand;
    }

    /**
     * @param int $num
     * @return int
     */
    protected function getSum($num)
    {
        do {
            $list = str_split($num);
            $num = array_sum($list);
        } while ($this->sumToOneDigit && $num > 9);

        return $num;
    }
}
