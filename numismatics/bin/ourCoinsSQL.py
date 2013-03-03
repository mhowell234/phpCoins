#/usr/bin/env python

from datetime import datetime
from utils import BASE_DIR, DB_DIR, OurCoinDB, writeStatements

#DB_DIR = 'DB'
f = open('%s/%s/our_Coin.txt' % (DB_DIR, OurCoinDB), 'r')

fields = ['year','name','ratingCategory','ratingValue','ratingAgency','isSilver','isGold','isProof','denomination','notes','pricePaid','isWrapped','origin','originDate','coinType','yearType']

isFirst=True
records = []

for line in f:
  if isFirst: isFirst = False; continue

  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  xline = line.replace(' ', '')
  if xline[0] == '#': continue

  tokens = line.split('|')

  data = {}
  i = 0
  for token in tokens:
    data[fields[i]] = token
    i = i + 1

  records.append(data)
f.close()

f = open('%s/data/mintCoinReference' % BASE_DIR, 'r')
mintCoinReference = {}
for line in f:
  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  xline = line.replace(' ', '')
  if xline[0] == '#': continue

  tokens = line.split('|')
  key = '%s||%s||%s||%s||%s||%s' % (tokens[0], tokens[1], tokens[2], tokens[3], tokens[4], tokens[5])
  key = key.lower()
  mintCoinReference[key] = tokens[6]

f.close()

originMapping = {'ebay':1, 'Grandma Betty':2, 'Raymond and Jane Howell':3}
ratingAgencyMapping = {}

stmts = ['USE OurCoinDB']
ocid = 1
for record in records:
  print '--START'
  print record; 
  sql = 'INSERT INTO OurCoin VALUES(%s' % ocid

  origin = record['origin']
  fieldsToIgnore = [] #'isSilver','isGold']
  year = record['year']
 
  additionalInfo = None
  ytokens = year.split(',')
  year = ytokens[0]
  if len(ytokens) > 1:
    additionalInfo = ','.join(ytokens[1:])
    
  mint = 'P'
  
  if len(year) > 4:
    mint = year[4:]
    year = year[:4]
  else: mint = 'P'


  coinLookup = '%s||%s||%s||%s||%s||%s' % (year, mint, record['name'], record['denomination'], record['coinType'], record['yearType'])
  coinLookup = coinLookup.lower()

  print coinLookup
  #print "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n"
  keys = mintCoinReference.keys()
  keys.sort()
  #for ke in keys:
  #print '%s== %s' % (ke, mintCoinReference[ke])

  if not mintCoinReference.has_key(coinLookup):    
    print 'record is: %s' % record
    print 'ref is %s' % coinLookup
    
    continue
    
  mcid = mintCoinReference[coinLookup]
  print '--%s--' % mcid
  sql = '%s, %s' % (sql, mcid)

  pricePaid = 'NULL'
  if record.has_key('pricePaid'):
    pricePaid = record['pricePaid']
  
  sql = '%s, %s' % (sql, pricePaid)   

  origin = 'NULL'
  if record.has_key('origin'):
    origin = originMapping[record['origin']]

  sql = '%s, %s' % (sql, origin)
  
  
  originDate = None
  if record.has_key('originDate'):
    originDate = myDatetime = datetime.strptime(record['originDate'], "%Y-%m-%d")
  
  if originDate:
    sql = '%s, "%s"' % (sql, originDate)
  else:
    sql = '%s, NULL' % (sql)
   
  isSilver = 0
  if record.has_key('isSilver'):
    isSilver = record['isSilver']
    if isSilver == 'Y':
      isSilver = 1
    else: isSilver = 0  
  sql = '%s, %s' % (sql, isSilver)

  isGold = 0
  if record.has_key('isGold'):
    isGold = record['isGold']
    if isGold == 'Y':
      isGold = 1
    else: isGold = 0  
  sql = '%s, %s' % (sql, isGold)
  
  isProof = 0
  if record.has_key('isProof'):
    isProof = record['isProof']  
  sql = '%s, %s' % (sql, isProof)
    
  sql = '%s);' % sql 

  stmts.append(sql)
  print sql
  ocid = ocid + 1
  print '--END'

writeStatements(stmts, 'OurCoin.sql', OurCoinDB)
