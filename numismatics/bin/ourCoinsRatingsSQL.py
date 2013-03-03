#/usr/bin/env python

from datetime import datetime
from utils import BASE_DIR, DB_DIR, OurCoinDB, writeStatements  

f = open('%s/%s/our_Coin.txt' % (DB_DIR, OurCoinDB), 'r')

fields =['year','name','ratingCategory','ratingValue','ratingAgency','isSilver','isGold','isProof','denomination','notes','pricePaid','isWrapped','origin','originDate','coinType','yearType']

isFirst=True
records = []

for line in f:
  if isFirst: isFirst = False; continue

  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  xline = line.replace(' ', '')
  if xline[0] == '#': continue
  
  tokens = line.split('|')

  print tokens
  data = {}
  i = 0
  for token in tokens:
    data[fields[i]] = token
    i = i + 1

  records.append(data)
f.close()


f = open('%s/data/ourCoinReference' % BASE_DIR, 'r')
ourCoinReference = {}
ourCoinToMintCoinMapping = {}

for line in f:
  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  xline = line.replace(' ', '')
  if xline[0] == '#': continue

  tokens = line.split('|')
  
  pricePaid = "%.2f" % float(tokens[6])
  
  key = '%s||%s||%s||%s||%s||%s||%s||%s' % (tokens[0], tokens[1], tokens[2], tokens[3], tokens[4], tokens[5], pricePaid, tokens[7])
  key = key.lower()
  ourCoinReference[key] = tokens[9]
  
  ourCoinToMintCoinMapping[tokens[9]] = tokens[8]

f.close()
  
  
f = open('%s/data/ratingAgencyReference' % BASE_DIR, 'r')
ratingAgencyReference = {}
for line in f:
  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  xline = line.replace(' ', '')
  if xline[0] == '#': continue

  tokens = line.split('|')
  key = tokens[0]
  key = key.lower()
  
  ratingAgencyReference[key] = tokens[2]

f.close()

print ratingAgencyReference

f = open('%s/data/coinGradeReference' % BASE_DIR, 'r')
coinGradeReference = {} 
coinGradeToConditionMapping = {}

for line in f:
  line = line.replace('\n','').strip()
  if line in [None, '']: continue

  xline = line.replace(' ', '')
  if xline[0] == '#': continue

  tokens = line.split('|')

  key = tokens[0]
  key = key.lower()
  
  coinGradeReference[key] = tokens[3]
  coinGradeToConditionMapping[tokens[3]] = tokens[2]

f.close()


#def getMintCoinValueId(mcid, title):
#   mcvid = None
#
#   title = title.lower()
#   
#   years = mintCoinValueReference.keys()
#   years.sort()
#   years.reverse()
#   
#   key = '%s||%s' % (mcid, title)
#   
#   for year in years:  
#     if not mintCoinValueReference[year].has_key(key):
#       continue
#   
#     mcvid = mintCoinValueReference[year][key]    
#       
#   return mcvid
   

def createKey(record):

  keyFields = []
  
  year = record['year']
  otherData = year[4:]
  year = year[:4]
  
  tokens = otherData.split(',')
  symbol = tokens[0]
  if len(symbol) == 0: symbol = 'P'
    
  keyFields.append(year)  
  keyFields.append(symbol)
  keyFields.append(record['name'])  
  keyFields.append(record['denomination'])  

  keyFields.append(record['coinType'])
  keyFields.append(record['yearType'])
  
  keyFields.append(record['pricePaid'])
  keyFields.append(record['originDate'])  


  key = '||'.join(keyFields)
  key = key.lower()
  
  return key


print coinGradeReference
print coinGradeToConditionMapping

stmts = ['USE %s;' % OurCoinDB]

kkeys = ourCoinReference.keys()
kkeys.sort()
for k in kkeys:
  print k

for record in records:
  
  sql = 'INSERT INTO Rating VALUES(NULL, %s, %s, %s, %s);'

  print record
  category = record['ratingCategory']
  value = record['ratingValue']
  
  if not category or not value:
    continue
    
 
  ratingValue = '%s-%s' % (category, value)
  ratingAgency = record['ratingAgency']
  
  raid = ratingAgencyReference[ratingAgency.lower()]
    
  key = createKey(record)
  print key
  
  ocid = ourCoinReference[key]
  mcid = ourCoinToMintCoinMapping[ocid]  

  print coinGradeReference.keys()
  cgid = coinGradeReference[ratingValue.lower()]
  print cgid  
  #print ocid, mcid, cgid

  #if cgid is None:
  #  cgid = 'NULL'
  
  originDate = datetime.strptime(record['originDate'], "%Y-%m-%d")
  
  if originDate:
    date = '"%s"' % originDate
  else:
    date = 'NULL'

  stmt = sql % (ocid, raid, cgid, date)
 
  
  stmts.append(stmt)
  
writeStatements(stmts, 'Rating.sql', OurCoinDB)
