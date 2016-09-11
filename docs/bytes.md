I18nExtension - Bytes filter
============================

Usage
-----

```
{{ value|bytes([format]) }}
```

where `format` can take the following values:

* `h` (default): the most appropriate unit will be selected
* `b`: display value in bytes (b)
* `K`: display value in kilobytes (Kb)
* `M`: display value in megabytes (Mb)
* `G`: display valuesin gigabytes (Gb)
* `T`: Display value in terbytes (Tb)
* `P`: Display vaue in petabytes (Pb)

Examples
--------

| markup  |  rendered as |
| ------  |  ----------- | 
| 1048'\|'bytes()  | 1Kb |
| 1048'\|'bytes('h') | 1Kb |
