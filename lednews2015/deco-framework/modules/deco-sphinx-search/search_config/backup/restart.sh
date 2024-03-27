#!/bin/sh
service sphinxsearch restart
indexer --config /etc/sphinxsearch/sphinx.conf --rotate --all
