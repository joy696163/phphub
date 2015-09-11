<?php

namespace Faker\Provider\pt_BR;

class PhoneNumber extends \Faker\Provider\PhoneNumber
{
    protected static $formats = array(
        '+## (##) #### - ####',
        '+## (##) 9#### - ####',
        '(##) #### - ####',
        '(##) ##### - ####',
        '+##(##) ####-####',
        '+##(##) 9####-####',
        '(##) ####-####',
        '(##) 9####-####',
        '+##(##) #### - ####',
        '+##(##) 9#### - ####',
        '(##)#### - ####',
        '(##)##### - ####',
        '+##(##)####-####',
        '+##(##)9####-####',
        '(##)####-####',
        '(##)9####-####',
        '#### - ####',
        '9#### - ####',
        '####-####',
        '9####-####',
        '## #### ####',
        '## 9#### ####',
    );
}
