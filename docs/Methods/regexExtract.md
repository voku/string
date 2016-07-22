# regexExtract
Given an expression with capture groups, this will return those captures.

## Description
`array regexExtract(string $pattern, string $options)`

Basically this is the same as `regexMatch()` but returns the array
of matches from `preg_match()` where as `regexMatch()` just returns
a boolean result.

### Parameters
* _string_ __$pattern__  
Regex pattern to match against.

* _string_ __$options__  
Matching conditions to be used.


### Return Value
_array_  
The matches discovered by `preg_match()`.

## Examples

## Changelog
```
commit cecb498d4031044811359c8feb6dcc36104829db
Author: Brad Jones <brad@bjc.id.au>
Date:   Mon Apr 18 17:31:20 2016 +1000

    Various other docblock fixes.

commit cb4957efce416b1679a51b29fe15b422e7f460d3
Author: Brad Jones <brad@bjc.id.au>
Date:   Mon Apr 18 14:41:48 2016 +1000

    Fixed up a bunch of bad indentation.

commit 07a1d45483229d3bc18cf3a9cdc782c01993c1cb
Author: Brad Jones <brad@bjc.id.au>
Date:   Thu Apr 14 17:03:20 2016 +1000

    Added new regexExtract method.

commit e5422500f59ab3e5ab91b53ffc14d45cc32e09bf
Author: Brad Jones <brad@bjc.id.au>
Date:   Thu Apr 14 17:02:56 2016 +1000

    Renamed matchesPattern to regexMatch

commit ceb21752a7b131831a756fedabb6eeef2d4afc66
Author: Brad Jones <brad@bjc.id.au>
Date:   Fri Apr 8 16:43:24 2016 +1000

    Unit test refactor now complete.

commit fe7c7b2d56888901c94e8fd150b7399d38bee5d4
Author: Brad Jones <brad@bjc.id.au>
Date:   Tue Mar 15 18:17:21 2016 +1100

    More refactoring's - still broken, still a WIP!

commit aa76a0507fc4398bd9f2a5b6744737e772fa621f
Author: Brad Jones <brad@bjc.id.au>
Date:   Fri Mar 11 19:20:05 2016 +1100

    So basically this is a refactored version of Stringy, we use traits to split up all the methods into categories for easier management. The refactor is still a WIP...
```