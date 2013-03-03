#/usr/bin/env python

from utils import BASE_DIR, DB_DIR, UsCoinDB, writeStatements 

f = open('%s/%s/coin_Metal_Composition.txt' % (DB_DIR, UsCoinDB), 'r')

fields = ['name', 'denomination', 'year', 'additionalInfo', 'coinTypeInfo', 'weightInGrams', 'silver', 'copper', 'nickel', 'manganese', 'gold', 'zinc']

isFirst=True
records = []

for line in f:
  if isFirst: isFirst = False; continue

  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  xline = line.replace(' ', '')
  if xline[0] == '#': continue

  tokens = line.split('|')

  data = {'name':'', 'denomination':'', 'year':'', 'weightInGrams':'', 
    'additionalInfo':'', 'coinTypeInfo':'', 'silver':'', 
    'copper':'', 'nickel':'', 'manganese':'', 'gold':'', 'zinc':''}
    
  i = 0
  for token in tokens:
    data[fields[i]] = token
    i = i + 1

  records.append(data)
f.close()

f = open('%s/data/preciousMetalReference' % BASE_DIR, 'r')
metalReference = {}
for line in f:
  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  tokens = line.split('|')
  key = tokens[0]
  key = key.lower()
  
  
  metalReference[key] = tokens[1]
f.close()


f = open('%s/data/mintCoinReference' % BASE_DIR, 'r')
mintCoinReference = {}
for line in f:
  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  tokens = line.split('|')
  key = '%s||%s||%s||%s||%s' % (tokens[0], tokens[2], tokens[3], tokens[4], tokens[5])
  key = key.lower()
  
  
  if not mintCoinReference.has_key(key):
    mintCoinReference[key] = {}

  symbol = tokens[1].lower()
  mcid = tokens[6]
  
  if (mintCoinReference[key].has_key(symbol)):
    print 'ERROR already exists %s -- %s' % (key, symbol)
  
  mintCoinReference[key][symbol] = mcid

keys = mintCoinReference.keys()
keys.sort()
for k in keys:
  #print k
  print mintCoinReference[k]

f.close()


def lookupMetal(name):
  print 'looking up %s' % name
  name = name.lower()
  
  print 'has key???: '
  
  if not metalReference.has_key(name):
    print 'NO'
    return None
  else:
    print 'YES' 
  return metalReference[name]
  
  
attrStmts = ['USE %s;' % UsCoinDB]
stmts = ['USE %s;' % UsCoinDB]

mcaid = 1
cmcid = 1

for record in records:

  print record
    
  coinName = record['name']
  year = record['year']
  denomination = record['denomination']
  coinTypeInfo = record['coinTypeInfo']
  mintYearInfo = record['additionalInfo']
  weight = record['weightInGrams']
  
  ## metals
  gold = record['gold']
  silver = record['silver']
  nickel = record['nickel']
  copper = record['copper']
  manganese = record['manganese']
  zinc = record['zinc']
  
  coinKey = '%s||%s||%s||%s||%s' % (year, coinName, denomination, mintYearInfo, coinTypeInfo)
  coinKey = coinKey.lower()

  print coinKey
  #continue
    
  if not mintCoinReference.has_key(coinKey):
    print '%s -- continuing...' % coinKey
    continue
    
  if str(weight) == '-1':
    print record
  
  mints = mintCoinReference[coinKey]
  for mint in mints:
  
    mcid = mints[mint]
    
    
    attrSql = 'INSERT INTO MintCoinAttribute VALUES(%s, %s, %s);' % (mcaid, mcid, weight)
    attrStmts.append(attrSql)
    
    print mcid
    if gold not in ['', -1, '-1', None]:
      pmid = lookupMetal('gold')
      percentage = gold
      sql = 'INSERT INTO CoinMetalComposition VALUES(%s, %s, %s, %s);' % (cmcid, mcaid, pmid, percentage)
      cmcid = cmcid + 1
      stmts.append(sql)

    if silver not in ['', -1, '-1', None]:
      pmid = lookupMetal('silver')
      percentage = silver
      sql = 'INSERT INTO CoinMetalComposition VALUES(%s, %s, %s, %s);' % (cmcid, mcaid, pmid, percentage)
      cmcid = cmcid + 1
      stmts.append(sql)

    if copper not in ['', -1, '-1', None]:
      pmid = lookupMetal('copper')
      percentage = copper
      sql = 'INSERT INTO CoinMetalComposition VALUES(%s, %s, %s, %s);' % (cmcid, mcaid, pmid, percentage)
      cmcid = cmcid + 1
      stmts.append(sql)

    if nickel not in ['', -1, '-1', None]:
      pmid = lookupMetal('nickel')
      percentage = nickel
      sql = 'INSERT INTO CoinMetalComposition VALUES(%s, %s, %s, %s);' % (cmcid, mcaid, pmid, percentage)
      cmcid = cmcid + 1
      stmts.append(sql)

    if manganese not in ['', -1, '-1', None]:
      pmid = lookupMetal('manganese')
      percentage = manganese
      sql = 'INSERT INTO CoinMetalComposition VALUES(%s, %s, %s, %s);' % (cmcid, mcaid, pmid, percentage)
      cmcid = cmcid + 1
      stmts.append(sql)

    if zinc not in ['', -1, '-1', None]:
      pmid = lookupMetal('zinc')
      percentage = zinc
      sql = 'INSERT INTO CoinMetalComposition VALUES(%s, %s, %s, %s);' % (cmcid, mcaid, pmid, percentage)
      cmcid = cmcid + 1
      stmts.append(sql)
            
    mcaid = mcaid + 1
    

writeStatements(attrStmts, 'MintCoinAttribute.sql', 'UsCoinDB')
writeStatements(stmts, 'CoinMetalComposition.sql', 'UsCoinDB')
