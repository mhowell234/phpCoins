#/usr/bin/env python

import os
from utils import BASE_DIR, writeStatements, ForeignCoinDB

mapping = {}

f = open('%s/data/foreignCoinReference' % BASE_DIR, 'r')

for line in f:

  line = line.replace('\n','').strip()

  if line == '': continue
  
  xline = line.replace(' ', '')
  if xline[0] == '#': continue
  
  tokens = line.split('|')
  
  key = '%s|%s' % (tokens[0], tokens[1])
  
  mapping[key] = tokens[2]
  
f.close()

print mapping

DB_DIR = 'DB'
coins = {}

PHOTO_DIR = 'photos/ForeignCoin'

for country in os.listdir('%s/%s' % (BASE_DIR, PHOTO_DIR)):
  if country == '.DS_Store': continue
  
  COUNTRY_TYPE_DIR = '%s/%s' % (PHOTO_DIR, country)
  
  for coinValue in os.listdir('%s/%s' % (BASE_DIR, COUNTRY_TYPE_DIR)):
    if coinValue == '.DS_Store': continue

    COUNTRY_VALUE_DIR = '%s/%s' % (COUNTRY_TYPE_DIR, coinValue)
  
    for coinType in os.listdir('%s/%s' % (BASE_DIR, COUNTRY_VALUE_DIR)):
      if coinType == '.DS_Store': continue

      COUNTRY_PHOTO_DIR = '%s/%s' % (COUNTRY_VALUE_DIR, coinType)    
    
      print 'looking in %s' % COUNTRY_PHOTO_DIR
      
      for photo in os.listdir('%s/%s' % (BASE_DIR, COUNTRY_PHOTO_DIR)):
        if photo == '.DS_Store': continue
    
        pPath = COUNTRY_PHOTO_DIR
        tokens = pPath.split('/')
        pPaths = [t for t in tokens ]
        pPaths.reverse()
        pPaths = pPaths[:2]
        pPath = '|'.join(pPaths)
    
        print 'photo is %s' % photo
        path = '%s/%s' % (COUNTRY_PHOTO_DIR, photo)
    
        if not coins.has_key(pPath):
          coins[pPath] = []
    
        print '%s -- %s' % (pPath, path)
        coins[pPath].append(path)


stmts = ['USE %s' % ForeignCoinDB]


keys = coins.keys()
for key in keys:
  photos = coins[key]
   
  if len(photos) == 0: continue
  
  print mapping.keys()
  cid = mapping[key]
  
  for photo in photos:
    caption = ''
    if 'obverse' in photo:
      caption = 'Obverse'
    else:  caption = 'Reverse'
  
    sql = "INSERT INTO CoinPhoto VALUES(NULL, %s, '%s', '%s');" % (cid, photo, caption)
    stmts.append(sql) 
 

writeStatements(stmts, 'CoinPhoto.sql', 'ForeignCoinDB')


THUMBNAIL_DIR = 'thumbnails/ForeignCoin'
coins = {}

for country in os.listdir('%s/%s' % (BASE_DIR, THUMBNAIL_DIR)):
  if country == '.DS_Store': continue
  
  COUNTRY_TYPE_DIR = '%s/%s' % (THUMBNAIL_DIR, country)
  
  for coinValue in os.listdir('%s/%s' % (BASE_DIR, COUNTRY_TYPE_DIR)):
    if coinValue == '.DS_Store': continue

    COUNTRY_VALUE_DIR = '%s/%s' % (COUNTRY_TYPE_DIR, coinValue)
  
    for coinType in os.listdir('%s/%s' % (BASE_DIR, COUNTRY_VALUE_DIR)):
      if coinType == '.DS_Store': continue

      COUNTRY_PHOTO_DIR = '%s/%s' % (COUNTRY_VALUE_DIR, coinType)    
    
      print 'looking in %s' % COUNTRY_PHOTO_DIR
      
      for photo in os.listdir('%s/%s' % (BASE_DIR, COUNTRY_PHOTO_DIR)):
        if photo == '.DS_Store': continue
    
        pPath = COUNTRY_PHOTO_DIR
        tokens = pPath.split('/')
        pPaths = [t for t in tokens ]
        pPaths.reverse()
        pPaths = pPaths[:2]
        pPath = '|'.join(pPaths)
    
        print 'photo is %s' % photo
        path = '%s/%s' % (COUNTRY_PHOTO_DIR, photo)
    
        if not coins.has_key(pPath):
          coins[pPath] = []
    
        print '%s -- %s' % (pPath, path)
        coins[pPath].append(path)


stmts = ['USE %s' % ForeignCoinDB]


keys = coins.keys()
for key in keys:
  photos = coins[key]
   
  if len(photos) == 0: continue
  
  print mapping.keys()
  cid = mapping[key]
  
  for photo in photos:
    caption = ''
    if 'obverse' in photo:
      caption = 'Obverse'
    else:  caption = 'Reverse'
  
    sql = "INSERT INTO CoinThumbnail VALUES(NULL, %s, '%s', '%s');" % (cid, photo, caption)
    stmts.append(sql) 
 

writeStatements(stmts, 'CoinThumbnail.sql', 'ForeignCoinDB')
