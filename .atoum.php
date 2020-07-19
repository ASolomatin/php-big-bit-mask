<?php

use mageekguy\atoum\reports;
use mageekguy\atoum\reports\coverage;
use mageekguy\atoum\writers\std;

if(getenv('TRAVIS') != false) {
    $coveralls = new reports\asynchronous\coveralls('src', getenv('COVERALLS_REPO_TOKEN'));
    $defaultFinder = $coveralls->getBranchFinder();
    $coveralls
        ->setBranchFinder(function () use ($defaultFinder) {
            if (($branch = getenv('TRAVIS_BRANCH')) === false) {
                $branch = $defaultFinder();
            }

            return $branch;
        })
        ->setServiceName(getenv('TRAVIS') ? 'travis-ci' : null)
        ->setServiceJobId(getenv('TRAVIS_JOB_ID') ?: null)
        ->addDefaultWriter();
    $runner->addReport($coveralls);
} else {
    $coverage = new coverage\html();
    $coverage->addWriter(new std\out());
    $coverage->setOutPutDirectory(__DIR__ . '/coverage');
    $runner->addReport($coverage);
}

$script->addDefaultReport();
