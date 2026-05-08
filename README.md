# Undergraduate Computer Programming Summer Internship at CCR

This repository contains three sample scripts and sample test data that
exemplify a real world data processing problem which we have encountered in our
project work. 

We would like you to familiarize yourself with one of the data
processing scripts and input test data. This script has a known bug that shows
when run against the real world data from `input/production.log`.  During your
interview call we would like to to talk about suggested fixes for the bug seen
when running the script with the real word data.

We have provided example scripts in three different languages. You
should only pick one of these to talk about.

# Description

We have a script that parses an input file and generates output in `json`
format. When you run the script with the sample log file `input/sample.log`
then it functions correctly and produces `json` output. The expected output of
the script is provided in `expected/sample.json`.

However, when the script is run against real world data from a production
system `input/production.log` it does not produce correct output in `json`
format.

# Examples

Below shows how to run each script for the three different programming languages.
You only need to talk about suggested changes in _one_ language and can pick whichever
you want to use.

### python3 version
```
python3 ./python3/parser.py ./input/sample.log
```

### javascript version (nodejs)
```
node ./javascript/parser.js ./input/sample.log
```

### php version
```
php ./php/parser.php ./input/sample.log
```
