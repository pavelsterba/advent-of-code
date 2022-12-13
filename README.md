[Advent of Code](https://adventofcode.com/) is an Advent calendar of small programming puzzles for a variety of skill sets and skill levels that can be solved in any programming language you like. If you want to solve it in **PHP**, this library can help you with common tasks as input downloading or solutions running.

## Installation & Setup

At first, install **AdventOfCode** for PHP as dependency via composer:

```
composer require pavelsterba/advent-of-code
```

It will add CLI located in `vendor/bin/aoc`. With it, you can configure it with:

```
php vendor/bin/aoc dotenv
```

### How to find my session cookie

One important think you need is value of your session cookie when you are logged in on Advent of Code website. Some small differences may exists between different browser, but basic way to find it is:

1. Go to [Advent of Code website](https://adventofcode.com/).
2. Make sure you are logged in.
3. Open Developer Tools with `F12`.
4. Go to `Application` tab.
5. In left menu, click on `Cookies` item and select Advent of Code domain.
6. Find cookie with name `session` and copy its value.

**Value of this cookie is sensitive information!** Make sure it will not be commited publicly, so add `.env` (where is it stored in your computer) into `.gitignore`.

## CLI commands

### dotenv

```
php vendor/bin/aoc dotenv [options]
```

```
Options:
  -y, --year=YEAR        Which year should be added as current
  -s, --session=SESSION  Session token for Advent of Code
```

Generate .env file during setup.

### input

```
php vendor/bin/aoc input [options] [--] <day>
```

```
Arguments:
  day                                 Which day to download

Options:
  -y, --year=YEAR                     Which year should be downloaded
  -o, --output                        Print input data
  -b, --boilerplate|--no-boilerplate  Generate boilerplate for solution
```

Download input data for given day. By default, it also creates boilerplate code for your solutions (more about it later).

### run

```
php vendor/bin/aoc run [options] [--] <day>
```

```
Arguments:
  day                   Which day to run

Options:
  -y, --year=YEAR       Which year are you solving
  -f, --first           Run first solution
  -s, --second          Run second solution
  -t, --test            Run on test data
```

Run solutions from generated boilerplate.

## Solutions

If you download input data with CLI and generate boilerplate, you will see that this library created some folder and files:

```
2022
└─ day-1
   ├─ intput.txt
   ├─ intput-test.txt
   └─ solution.php
```

`input.txt` contains input data for your puzzle.

`input-test.txt` is by default empty file, but its purpose is to add example input data for puzzle to be able to run your solution against small dataset with expected output.

`solution.php` is main file for your solutions. It contains two method - `first()` and `second()` for easier and harder part of puzzle. Just `return` your solution value and run it.
