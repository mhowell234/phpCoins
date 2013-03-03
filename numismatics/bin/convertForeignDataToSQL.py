#/usr/bin/env python

import shutil, os
from utils import BASE_DIR, DB_DIR, LINE_SEPARATOR, CommonDB, ForeignCoinDB, convertFieldsToColumns, convertFileNameToTableId, convertFileNameToTableName, convertIdsForMax, convertTokenToFieldName, createMapping, createMultiKeyMapping, createSpecialMapping, fileToRecord, recordToSQL, writeStatements 

COUNTRIES_PATH = "%s/%s/countries" % (DB_DIR, ForeignCoinDB)


def createMintCoinValueSQL(mintCoins, coinConditionMapping, mcvid):
  stmts = []

  keys = mintCoins.keys()
  for key in keys:
    #cvid = key
    data = mintCoins[key]

    BASE_SQL = 'INSERT INTO MintCoinValue VALUES('

    columns = data['columns']
    colVals = [column['column'].upper() for column in columns if column['column'] not in ['year', 'mintage', 'paren','additionalInfo','km'] ]

    colVals.sort()

    year = 2013
    tid = data['tableId']
    records = data['data']
    for record in records:
      print record
      for column in colVals:

        newcol = column.lower()
        if not record.has_key(newcol):
          #print '%s is not present in %s' % (newcol, record)
          continue

        #print 'looking up %s in %s' % (column.lower(), record)
        ccid = coinConditionMapping[column]
        value = record[column.lower()]
        
        
        if value is not None and float(value) == -1.0: continue

        sql = '%s%s, %s, %s, %s, %s);' % (BASE_SQL, mcvid, year, record[tid], ccid, value)

        mcvid = mcvid + 1 
        stmts.append(sql)

  return (mcvid, stmts)


def createMintCoinSQL(mintCoins, mintMapping, denomYearMapping):
  stmts = []

  keys = mintCoins.keys()
  for key in keys:
    cvid = key
    data = mintCoins[key]

    BASE_SQL = 'INSERT INTO %s VALUES(' % data['tableName']

    #columns = data['columns']
    tid = data['tableId']
    records = data['data']
    for record in records:
      sql = '%s%s,' % (BASE_SQL, record[tid])

      year = record['year']
      tokens = year.split(',')
      year = tokens[0].strip()
      additionalInfo = ''
      if len(tokens) > 1:
        additionalInfo = tokens[1].replace(':', ' ').strip()

      if ',' in year:
        year_tokens = year.split(',')
        year = year_tokens[0]
        #title = year_tokens[1]
         
      mint = ''
      if len(year) > 4:
        mint = year[4:]
        year = year[:4]
   
      print 'looking up mint in %s' % mintMapping
       
      mid = mintMapping[mint]

      additionalKey = ''
      if record.has_key('additionalInfo'):
        additionalKey = record['additionalInfo']

      dyKey = '%s||%s||%s' % (cvid, year, additionalKey)
      keys = denomYearMapping.keys()
      keys.sort()
      #for k in keys: 
      #  print k
      #print record

      dKeys = denomYearMapping.keys()
      dKeys.sort()
      #for dk in dKeys:
      #  print '%s -- %s\n' % (dk, denomYearMapping[dk])
      print denomYearMapping
      cyid = denomYearMapping[dyKey]
   
      mintage = record['mintage']
      if mintage in ['-1', -1]: mintage = "NULL"
      elif mintage in [None, '', '0']: mintage = 0

      proofCount = 0
      if record.has_key('paren'):
        proofCount = record['paren']
      if proofCount in  [None, '', '0']:  proofCount = 0
      elif proofCount in ['-1', -1]: proofCount = "NULL"
       
      sql = '%s %s, %s, "%s", %s, %s);' % (sql, cyid, mid, additionalInfo, mintage, proofCount)
       
      stmts.append(sql)

  return stmts 


def fileToRecord(fileName, specialCol=None, specialColValue=None, uid=None, tableName=None, tableId=None):

  if not tableName: tableName = convertFileNameToTableName(fileName)
  if not tableId: tableId = convertFileNameToTableId(fileName)

  output = {'data': [], 'tableName': tableName, 'tableId': tableId, 'columns':[]}

  fields = []
  fieldLine = True
  
  if not uid:
    uid = 1
  else: 
    print 'UID exists..is --%s--' % uid
    uid = int(uid)
  
  f = open(fileName, 'r')
  
  for line in f:
    line = line.replace('\n','').strip()

    if len(line) == 0:
      continue

    xline = line.replace(' ', '')
    if xline[0] == '#': continue

    tokens = line.split('|')

    if fieldLine: 
      for token in tokens:
        token = token.strip()
        token = convertTokenToFieldName(token)
        fields.append(token)        
      fieldLine = False
      
      if specialCol == 'country': colType='int'
      else: colType='str'
      
      output['columns'] = convertFieldsToColumns(fields, tableName, specialCol, colType)
      continue 
    
    record = {}
    record[tableId] = uid

    #print line
    tokenId = 0
    for token in tokens:
      fieldName = fields[tokenId]

      token = token.replace(LINE_SEPARATOR, '\n\n')
      
      if fieldName == 'year':
        token = token.replace(':', ' ')
      record[fieldName] = token
      tokenId = tokenId + 1
  
    if specialCol:
      record[specialCol] = specialColValue
      
    uid = uid + 1
    output['data'].append(record)
  
  f.close()
 
  return (output, uid)



OUTPUT_DIR = '%s/sqlImport' % BASE_DIR

try:
  shutil.rmtree('%s/%s' % (OUTPUT_DIR, ForeignCoinDB) )
except:
  print 'doesnt exist'

# Foreign Countries
(countries, dummy) = fileToRecord('%s/%s/foreign_Country.txt' % (DB_DIR, ForeignCoinDB))
countriesStmts = recordToSQL(countries)
writeStatements(countriesStmts, '%s.sql' % countries['tableName'], ForeignCoinDB)
countryMapping = createMapping(countries, 'abbreviation', 'fcid')

cvid = 1
cid = 1
cyid = 1
mid = 1
mdid = 1
allStatements = []
allValueStatements = []
mcid = 1
maxId = 0
mcvid = 1 
  

def coinTypeFromPath(path, coin, mapping, replacePath):
  denomination = path.replace('%s/' % replacePath, '')
  key = '%s||%s' % (coin, denomination)
  print 'looking up %s in %s' % (key, mapping)
  return mapping[key]


# Sheldon Rating categories 
(sheldonRatingCategories, srcid) = fileToRecord('%s/%s/sheldon_Rating_Category.txt' % (DB_DIR,CommonDB), None, None, None)
sheldonRatingCategoriesMapping = createMapping(sheldonRatingCategories, 'category', 'srcid')

# Sheldon Rating scale values
(sheldonRatingScale, srsid) = fileToRecord('%s/%s/sheldon_Rating_Scale.txt' % (DB_DIR, CommonDB))
sheldonRatingScaleMapping = createMapping(sheldonRatingScale, 'title', 'srsid')



for country in os.listdir(COUNTRIES_PATH):

  mintCoins = {}
  MINT_COIN_PATH = "%s/%s/mintCoin" % (COUNTRIES_PATH, country)

  # Coin Values (Denominations)
  (coinValues, cvid) = fileToRecord('%s/%s/coin_Value.txt' % (COUNTRIES_PATH, country), 'country', country, cvid)
  coinValuesStmts = recordToSQL(coinValues, [{'mapping':countryMapping, 'field':'country'}])
  writeStatements(coinValuesStmts, '%s.sql' % coinValues['tableName'], ForeignCoinDB)
  coinValueMapping = createMapping(coinValues, 'name', 'cvid')


  # Coin types (Barber, Liberty Walking, etc)
  (coins, cid) = fileToRecord('%s/%s/coin.txt' % (COUNTRIES_PATH, country), None, None, cid)
  coinStmts = recordToSQL(coins, [{'mapping':coinValueMapping, 'field':'type'}])
  writeStatements(coinStmts, '%s.sql' % coins['tableName'], ForeignCoinDB)
  coinDenominationMapping = createMultiKeyMapping(coins, ['name', 'type'], 'cid')


  # Information about a coin made in a specific year
  (coinYears, cyid) = fileToRecord('%s/%s/coin_Year.txt' % (COUNTRIES_PATH, country), None, None, cyid)
  coinYearsStmts = recordToSQL(coinYears, 
	[{'mapping':coinDenominationMapping, 'field':'coin||denomination'}],
        ['denomination'])
  writeStatements(coinYearsStmts, '%s.sql' % coinYears['tableName'], ForeignCoinDB)
  coinYearMapping = createSpecialMapping(coinYears, coinDenominationMapping, 'coin||denomination', 'year||additionalInfo', 'cyid')

  # Foreign Mints
  (mints, mid) = fileToRecord('%s/%s/mint.txt' % (COUNTRIES_PATH, country), None, None, mid)
  
  fcid = countryMapping[country]
  for info in mints['data']:
    info['country'] = fcid
  mints['columns'].append({'column':'country', 'type':'int'})  
  
  mintStmts = recordToSQL(mints)
  writeStatements(mintStmts, '%s.sql' % mints['tableName'], ForeignCoinDB)
  mintMapping = createMapping(mints, 'mint', 'mid')
  mintMarkMapping = createMapping(mints, 'symbol', 'mid')


  # Dates mints were in operation
  (mintDates, mdid) = fileToRecord('%s/%s/mint_Date.txt' % (COUNTRIES_PATH, country), None, None, mdid)
  mintDatesStmts = recordToSQL(mintDates, [{'mapping':mintMapping, 'field':'mint'}])
  writeStatements(mintDatesStmts, '%s.sql' % mintDates['tableName'], ForeignCoinDB)

  print MINT_COIN_PATH
  for (path, dirs, files) in os.walk(MINT_COIN_PATH):
    for coin in files:
      coinWithoutExt = coin.replace('.txt','')
      print 'mapping is %s' % coinDenominationMapping
      cvid2 = coinTypeFromPath(path, coinWithoutExt, coinDenominationMapping, MINT_COIN_PATH)
      if not mintCoins.has_key(cvid):
        mintCoins[cvid2] = []

      (coinData, mcid) = fileToRecord('%s/%s' % (path, coin), None, None, mcid, 'MintCoin', 'mcid')
      maxData = convertIdsForMax(coinData, maxId)    
      coinData = maxData[0]
      maxId = maxData[1]    

      mintCoins[cvid2] = coinData
      
  keys = mintCoins.keys()
  keys.sort()
  for k in keys:
    print '%s: %s' % (k, mintCoins[k])
  
  print coinYearMapping
  mintCoinStatements = createMintCoinSQL(mintCoins, mintMarkMapping, coinYearMapping)
  
  allStatements = allStatements + mintCoinStatements

  # Different values for a coin based on condition
  (mcvid, mintCoinValueStatements) = createMintCoinValueSQL(mintCoins, sheldonRatingScaleMapping, mcvid) #coinConditionMapping)
  
  allValueStatements = allValueStatements + mintCoinValueStatements;
    
writeStatements(allStatements, '%s.sql' % 'MintCoin', ForeignCoinDB)
writeStatements(allValueStatements, '%s.sql' % 'MintCoinValue', ForeignCoinDB)
  
  