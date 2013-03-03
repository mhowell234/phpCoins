#!/usr/bin/env python

import urllib2

URL = 'http://www.usacoinbook.com/coin-melt-values/'
START_LINE = '<h3 class="left">Current Precious and Base Metal Bullion Spot Prices:</h3>'

OZ_CON = .0321507466
LB_CON = .00220462262
MTON_CON = .000001


page = urllib2.urlopen(URL)
html = page.read()
page.close()

lines = html.split('\n')

count = 0

values = []
startValues = False
endValues = True
hasBlank = False

for line in lines:
  line = line.replace('\n','')
  line = line.strip()

  if line == START_LINE:
    startValues = True
    continue

  if not startValues:
    continue

  if line == '' and hasBlank:
    break

  if line == '':
    hasBlank = True
    continue

  values.append(line)

size = len(values)

if size == 10:
  values = values[3:]

metals = {}

for line in values:
  for bad in ['<b>','</b>','&nbsp;','</center>','<br />']:
    line = line.replace(bad, '')

  line = line.strip()

  tokens = line.split(':')

  metal = tokens[0].strip()

  rest = ':'.join(tokens[1:]).strip()

  tokens = rest.split(' per ')

  price = tokens[0].replace('$','')

  factor = tokens[1]


  pricePerGram = None

  if factor.lower() == 'ounce':
    pricePerGram = float(price) * OZ_CON
    v = OZ_CON
  elif factor.lower() == 'pound':
    pricePerGram = float(price) * LB_CON
    v = LB_CON
  elif factor.lower() == 'ton':
    pricePerGram = float(price) * MTON_CON
    v = MTON_CON
 
  print "%s - %s" % (count, line)
  print '--%s--%s--%s--' % (metal, price, factor)
  print '%s * %s = %s' % (price, v, pricePerGram)
  count = count + 1

  metals[metal] = {}
  metals[metal]['metal'] = metal
  metals[metal]['price'] = price
  metals[metal]['conversion'] = v
  metals[metal]['bulkName'] = factor.lower()  
  metals[metal]['pricePerGram'] = pricePerGram

keys = metals.keys()
keys.sort()

f = open('../DB/CommonDB/metalPrices.txt', 'w')
for key in keys:
  f.write('%s|%s|%s|%f|%s\n' % (key, metals[key]['price'], metals[key]['bulkName'], metals[key]['conversion'], metals[key]['pricePerGram']) )
f.close()

 
