# dba-shim

Shim for DBA PHP functions on systems without the extension installed

Requires PHP >= 5.4.

## Usage

The library provides replacements of select `dba_*` functions, facilitating moving an application to a server without the extension installed. Ideally, just load it and your legacy code might work as before. 

## Contributing

This is just a quick and dirty implementation that helped me in a project. Pull requests are appreciated. As long as you keep the attribution, feel free to reuse the code in your own libraries. 

## TODO

- Support additional handlers (currently, only inifile is implemented)
- Implement writing and deleting data
- Composer support
- Tests
