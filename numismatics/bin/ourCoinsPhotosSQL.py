#/usr/bin/env python

import os
from utils import BASE_DIR, DB_DIR, OurCoinDB, writeStatements  

f = open('%s/%s/our_Coin.txt' % (DB_DIR, OurCoinDB), 'r')

isFirst=True
coins = {}
coins2 = {}
ocidMapping = {}

ocid = 1

for line in f:
  if isFirst: isFirst = False; continue

  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  xline = line.replace(' ', '')
  if xline[0] == '#': continue

  coins[line] = []
  coins2[line] = []
  print '%d: %s' % (ocid, line)
  ocidMapping[line] = ocid
  ocid = ocid + 1
  
f.close()


print ocidMapping


PHOTO_DIR = 'photos/UsOurCoin'

for (path, dirs, files) in os.walk('%s/%s' % (BASE_DIR,PHOTO_DIR)):
  for photo in files:
    if photo == '.DS_Store': continue
    
    print '%s -- %s' % (path, photo)
    pPath = path.replace('%s/%s/' % (BASE_DIR,PHOTO_DIR), '')
    print pPath
    print coins.keys()
    coins[pPath].append(photo)

print coins

stmts = ['USE %s;' % OurCoinDB]

keys = coins.keys()
for key in keys:
  photos = coins[key]
   
  if len(photos) == 0: continue
  
  coinId = ocidMapping[key]
  
  for photo in photos:
    sql = "INSERT INTO OurCoinPhoto VALUES(NULL, %s, '%s/%s/%s', '');" % (coinId, PHOTO_DIR, key, photo)
    stmts.append(sql) 
 

writeStatements(stmts, 'OurCoinPhoto.sql', OurCoinDB)

print ocidMapping


THUMBNAIL_DIR = 'thumbnails/UsOurCoin'
coins = coins2

for (path, dirs, files) in os.walk('%s/%s' % (BASE_DIR,THUMBNAIL_DIR)):
  for photo in files:
    if photo == '.DS_Store': continue
    
    print '%s -- %s' % (path, photo)
    pPath = path.replace('%s/%s/' % (BASE_DIR,THUMBNAIL_DIR), '')
    print pPath
    coins[pPath].append(photo)

stmts = ['USE %s;' % OurCoinDB]

keys = coins.keys()
for key in keys:
  photos = coins[key]
   
  if len(photos) == 0: continue
  
  coinId = ocidMapping[key]
  
  for photo in photos:
    sql = "INSERT INTO OurCoinThumbnail VALUES(NULL, %s, '%s/%s/%s', '');" % (coinId, THUMBNAIL_DIR, key, photo)
    stmts.append(sql) 
 

writeStatements(stmts, 'OurCoinThumbnail.sql', OurCoinDB)

print ocidMapping