#!/bin/bash

wget http://cdn.sencha.com/ext/gpl/ext-4.2.1-gpl.zip -O extjs.zip && \
unzip extjs.zip -d web && \
mv web/ext-4.2.* web/ext-4.2 && \
rm extjs.zip