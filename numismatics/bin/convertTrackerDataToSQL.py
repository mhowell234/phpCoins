#/usr/bin/env python

      
from datetime import datetime
from utils import BASE_DIR, DB_DIR, TrackerDB, fileToRecord, recordToSQL, writeStatements, changeColumnType, getCoinValueReference,  getCoinReference, getCoinYearReference, getMintReference, getMintSymbolReference, getMintCoinReference, getRatingCategoryReference, getRatingValueReference, lookupReferenceValue

trackers = fileToRecord('%s/%s/tracker_Search.txt' % (DB_DIR, TrackerDB))

changeColumnType(trackers, 'mint', 'int')
changeColumnType(trackers, 'grade', 'int')
changeColumnType(trackers, 'gradeCategory', 'int')

coinValueReference = getCoinValueReference()
coinReference = getCoinReference()
coinYearReference = getCoinYearReference()
mintReference = getMintReference()
mintSymbolReference = getMintSymbolReference()
mintCoinReference = getMintCoinReference()
ratingCategoryReference = getRatingCategoryReference()
ratingValueReference = getRatingValueReference()

print coinValueReference
print coinReference
print coinYearReference
print mintReference
print mintSymbolReference
print mintCoinReference
print ratingCategoryReference
print ratingValueReference

newData = []
emailData = {}

trackerData = trackers['data']
i =0

for data in trackerData:

  value = data['value']
  coin = data['coin']
  year = data['year']
  yearInfo = data['yearInfo']
  mint = data['mint']
  coinInfo = data['coinInfo']
  
  data['mintCoin'] = ''
  
  print '%s\n' % i
  print data

  cvid = lookupReferenceValue([value], coinValueReference)
  data['value'] = cvid
  
  if data['coin']:
    cid = lookupReferenceValue([coin, value], coinReference)
    data['coin'] = cid
    
  if data['year']:
    cyid = lookupReferenceValue([year, coin, value, yearInfo], coinYearReference) 
    data['year'] = cyid
  
    if data['mint']:
      # if year and mint, get mint coin info
      symbol = lookupReferenceValue([mint], mintSymbolReference)

      mcid = lookupReferenceValue([year, symbol, coin, value, yearInfo, coinInfo], mintCoinReference)
      
      data['mintCoin'] = mcid
      data['mint'] = ''
      
  elif data['mint']:
    # If not year, just get mint
    mid = lookupReferenceValue([mint], mintReference) 
    data['mint'] = mid
    data['mintCoin'] = ''
    
  if data['gradeCategory']:
    gradeCategory = data['gradeCategory']
    srcid = lookupReferenceValue([gradeCategory], ratingCategoryReference)  
    
    data['gradeCategory'] = srcid

  if data['grade']:
    grade = data['grade']
    srsid = lookupReferenceValue([grade], ratingValueReference)  
    
    data['grade'] = srsid
    
  
  if data['emails']:
    emails = data['emails']
    tokens = [ t.strip() for t in emails.split('##') if t ]
    
    emailData[i] = tokens
    
  if data.has_key('yearInfo'): del data['yearInfo'] 
  if data.has_key('coinInfo'): del data['coinInfo']
  if data.has_key('emails'): del data['emails']
  
  newData.append(data)
  i = i + 1

trackers['data'] = newData  

cols = trackers['columns']
newC = []
for c in cols:
  if c['column'] in ['yearInfo','coinInfo','gradeCategory','grade','ratingAgency','sellerRating','emails']: continue
  
  if c['column'] == 'mint':
    newC.append({'column':'mintCoin','type':'int'})
  elif c['column'] == 'auctionEndTime':
    newC.append({'column':'auctionEndTime', 'type':'int'})
    newC.append({'column':'gradeCategory', 'type':'int'})
    newC.append({'column':'grade', 'type':'int'})
    newC.append({'column':'ratingAgency', 'type':'str'})
    newC.append({'column':'sellerRating', 'type':'int'})
    continue

  newC.append(c)
trackers['columns'] = newC


print trackers

print '\n\n\n\n'
trackerStmts = recordToSQL(trackers)

print trackerStmts
writeStatements(trackerStmts, '%s.sql' % trackers['tableName'], TrackerDB)



emailAddresses = {'tableName':'EmailAddress', 'tableId':'eaid', 'data':[],
   'columns':[{'column':'tsid', 'type':'int'},{'column':'address', 'type':'str'},]}
   
keys = emailData.keys()
keys.sort()
eaid = 1
for key in keys:
  tsid = key
  for email in emailData[key]:
    info = {'eaid':eaid, 'tsid':key+1, 'address':email}    
    emailAddresses['data'].append(info)
    eaid = eaid + 1
    
print emailAddresses    
emailAddressStmts = recordToSQL(emailAddresses)
print emailAddressStmts
writeStatements(emailAddressStmts, '%s.sql' % emailAddresses['tableName'], TrackerDB)
