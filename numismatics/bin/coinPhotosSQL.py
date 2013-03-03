# /usr/bin/env python

import os
from utils import BASE_DIR, UsCoinDB, writeStatements

mapping = {}

f = open('%s/data/coinReference' % BASE_DIR, 'r')

for line in f:

  line = line.replace('\n', '').strip()

  if line == '': continue
  
  xline = line.replace(' ', '')
  if xline[0] == '#': continue
  
  tokens = line.split('|')
  
  key = '%s|%s' % (tokens[0], tokens[1])
  
  mapping[key] = tokens[2]
  
f.close()

print mapping

DB_DIR = '%s/DB' % BASE_DIR
coins = {}

PHOTO_DIR = 'photos/UsCoin' 

for (path, dirs, files) in os.walk('%s/%s' % (BASE_DIR, PHOTO_DIR)):
  for photo in files:
    if photo == '.DS_Store': continue
    
    pPath = path.replace('%s/%s/' % (BASE_DIR, PHOTO_DIR), '')
    tokens = pPath.split('/')
    paths = [t for t in tokens ]
    paths.reverse()
    
    pPath = '|'.join(paths)
   
    if not coins.has_key(pPath):
      coins[pPath] = []
    
    print '%s -- %s' % (pPath, photo)
    coins[pPath].append(photo)


stmts = ['USE %s' % UsCoinDB]


keys = coins.keys()
for key in keys:
  photos = coins[key]
   
  if len(photos) == 0: continue
  
  cid = mapping[key]
  
  tokens = key.split('|')
  tokens.reverse()
  keyAsPath = '/'.join(tokens)
  # keyAsPath = key.replace('|', '/')
  print keyAsPath
  
  for photo in photos:
    caption = ''
    if 'obverse' in photo:
      caption = 'Obverse'
    else:  caption = 'Reverse'
  
    photoName = '%s/%s' % (keyAsPath, photo)
    if photoName.count("'") > 0:
      print 'HAS QUOTE -- %s' % photoName
      photoName = photoName.replace("'", "''")
    else:
      print 'NO QUOTE -- %s' % photoName
    
    
    sql = "INSERT INTO CoinPhoto VALUES(NULL, %s, '%s/%s', '%s');" % (cid, PHOTO_DIR, photoName, caption)
    stmts.append(sql) 
 

writeStatements(stmts, 'CoinPhoto.sql', 'UsCoinDB')


THUMBNAILS_DIR = 'thumbnails/UsCoin' 
coins = {}

for (path, dirs, files) in os.walk('%s/%s' % (BASE_DIR, THUMBNAILS_DIR)):
  for photo in files:
    if photo == '.DS_Store': continue
    
    pPath = path.replace('%s/%s/' % (BASE_DIR, THUMBNAILS_DIR), '')
    tokens = pPath.split('/')
    paths = [t for t in tokens ]
    paths.reverse()
    
    pPath = '|'.join(paths)
   
    if not coins.has_key(pPath):
      coins[pPath] = []
    
    print 'THDIR: %s -- %s' % (pPath, photo)
    coins[pPath].append(photo)


stmts = ['USE %s' % UsCoinDB]


keys = coins.keys()
for key in keys:
  photos = coins[key]
   
  if len(photos) == 0: continue
  
  cid = mapping[key]
  
  tokens = key.split('|')
  tokens.reverse()
  keyAsPath = '/'.join(tokens)
  # keyAsPath = key.replace('|', '/')
  print keyAsPath
  
  for photo in photos:
    caption = ''
    if 'obverse' in photo:
      caption = 'Obverse'
    else:  caption = 'Reverse'

    photoName = '%s/%s' % (keyAsPath, photo)
    if photoName.count("'") > 0:
      print 'HAS QUOTE -- %s' % photoName
      photoName = photoName.replace("'", "''")
    else:
      print 'NO QUOTE -- %s' % photoName

    sql = "INSERT INTO CoinThumbnail VALUES(NULL, %s, '%s/%s', '%s');" % (cid, THUMBNAILS_DIR, photoName, caption)
    stmts.append(sql) 
 

writeStatements(stmts, 'CoinThumbnail.sql', 'UsCoinDB')
