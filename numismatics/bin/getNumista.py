#!/bin/sh

fileName = 'toDownload'

i = 101

stmts = []

while i < 2000:
  stmts.append('curl -o g%s.jpg http://en.numista.com/catalogue/photos/etats-unis/g%s.jpg' % (i, i))
  i = i + 1

fName = '../numista/toDownload.sh'

f = open(fileName, 'w')

for stmt in stmts:
  f.write('%s\n' % stmt)

f.close()
