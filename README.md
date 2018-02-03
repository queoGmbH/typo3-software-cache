[![Build Status](https://travis-ci.org/queoGmbH/typo3-software-cache.svg?branch=master)](https://travis-ci.org/queoGmbH/typo3-software-cache)

# TYPO3 Software Cache
> *TL;DR*
> Software based library to cache TYPO3 Requests.

## Allgemeine Informationen

### Ansprechpartner
Entwickler:
* [Stephan Lindner]
* [Ralf Michael]

## Konzept
> to be done
## Installation

For easy installation add an index.php.dist file to your project and add the following commands to your composer.json

````json
"chmod 654 ./vendor/queo/typo3-software-cache/src/Scripts/installCache.sh",
"./vendor/queo/typo3-software-cache/src/Scripts/installCache.sh index.php.dist",
````

We added an starting point with a simple index.php.dist in the base folder, you can use for your first steps or easy websites. It will cache the requests in the filesystem.

## Qualitätssicherung
### Unittests
````bash
bin/phpunit
````
### Codemetriken erstellen
[Phpmetrics](http://www.phpmetrics.org/) global installieren
````bash
composer global require phpmetrics/phpmetrics:~2
````
Bericht für Metriken erzeugen
````bash
phpmetrics --report-html=build/metric --git --quiet src\
````
### Weiterentwicklung
__Goldene Regeln:__
- SOLID Prinzipien einhalten!
- Metriken beachten

## Anleitung
> to be done
## FAQ
> 42