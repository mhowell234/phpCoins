#/usr/bin/env python

import os
from utils import DB_DIR, CommonDB, UsCoinDB, convertIdsForMax, createMapping, createMultiKeyMapping, createSpecialMapping, fileToRecord, recordToSQL, writeStatements 

MINT_COIN_PATH = "%s/%s/mintCoin" % (DB_DIR, UsCoinDB)


def createMintCoinValueSQL(mintCoins, scaleMapping):
  stmts = []

  mcvid = 1
  keys = mintCoins.keys()
  for key in keys:
    #cvid = key
    data = mintCoins[key]

    BASE_SQL = 'INSERT INTO MintCoinValue VALUES('

    columns = data['columns']
    colVals = [column['column'].upper() for column in columns if column['column'] not in ['year', 'mintage', 'paren','additionalInfo'] ]

    colVals.sort()

    year = 2013
    tid = data['tableId']
    records = data['data']
    for record in records:
      for column in colVals:

        newcol = column.lower()
        if not record.has_key(newcol):
          print '%s is not present in %s' % (newcol, record)
          continue

        print 'looking up %s in %s' % (column.lower(), record)
        print 'mapping %s' % scaleMapping
        ccid = scaleMapping[column]
        value = record[column.lower()]
        
        
        if value is not None and float(value) == -1.0: continue

        sql = '%s%s, %s, %s, %s, %s);' % (BASE_SQL, mcvid, year, record[tid], ccid, value)

        mcvid = mcvid + 1 
        stmts.append(sql)

  return stmts


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
         
      mint = 'P'
      if len(year) > 4:
        mint = year[4:]
        year = year[:4]

      if mint == 'P':
        mint = 'Philadelphia'
      elif mint == 'CC': 
        mint = 'Carson City'
      elif mint == 'C':
        mint = 'Charlotte'
      elif mint == 'O':
        mint = 'New Orleans'
      elif mint == 'S':
        mint = 'San Francisco'
      elif mint == 'W':
        mint = 'West Point'
      elif mint == 'D' and int(year) >= 1838 and int(year) <= 1861:
        mint = 'Dahlonega'
      elif mint == 'D' and int(year) >= 1906:
        mint = 'Denver'
      else:
        print 'ISSUE: %s' % mint 

      mid = mintMapping[mint]

      additionalKey = ''
      if record.has_key('additionalInfo'):
        additionalKey = record['additionalInfo']

      dyKey = '%s||%s||%s' % (cvid, year, additionalKey)
      keys = denomYearMapping.keys()
      keys.sort()
      for k in keys: 
        print k
        print record

      dKeys = denomYearMapping.keys()
      dKeys.sort()
      for dk in dKeys:
        print '%s -- %s\n' % (dk, denomYearMapping[dk])
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

# Agencies that rate coins
ratingAgencies = fileToRecord('%s/%s/rating_Agency.txt' % (DB_DIR, CommonDB))
ratingAgenciesStmts = recordToSQL(ratingAgencies)
writeStatements(ratingAgenciesStmts, '%s.sql' % ratingAgencies['tableName'], CommonDB)
ratingAgencyMapping = createMapping(ratingAgencies, 'name', 'raid')
ratingAgencyFullNameMapping = createMapping(ratingAgencies, 'fullName', 'raid')

# Coin origins
coinOrigins = fileToRecord('%s/%s/coin_Origin.txt' % (DB_DIR,CommonDB))
coinOriginsStmts = recordToSQL(coinOrigins)
writeStatements(coinOriginsStmts, '%s.sql' % coinOrigins['tableName'], CommonDB)
coinOriginMapping = createMapping(coinOrigins, 'name', 'coid')

# Sheldon Rating categories 
sheldonRatingCategories = fileToRecord('%s/%s/sheldon_Rating_Category.txt' % (DB_DIR,CommonDB))
sheldonRatingCategoriesStmts = recordToSQL(sheldonRatingCategories)
writeStatements(sheldonRatingCategoriesStmts, '%s.sql' % sheldonRatingCategories['tableName'], CommonDB)
print sheldonRatingCategories
sheldonRatingCategoriesMapping = createMapping(sheldonRatingCategories, 'category', 'srcid')

# Sheldon Rating scale values
sheldonRatingScale = fileToRecord('%s/%s/sheldon_Rating_Scale.txt' % (DB_DIR,CommonDB))
sheldonRatingScaleStmts = recordToSQL(sheldonRatingScale, [{'mapping':sheldonRatingCategoriesMapping, 'field':'category'}])
writeStatements(sheldonRatingScaleStmts, '%s.sql' % sheldonRatingScale['tableName'], CommonDB)
sheldonRatingScaleMapping = createMapping(sheldonRatingScale, 'title', 'srsid')

print sheldonRatingScale

# Coin Values (Denominations)
coinValues = fileToRecord('%s/%s/coin_Value.txt' % (DB_DIR,UsCoinDB))
coinValuesStmts = recordToSQL(coinValues)
writeStatements(coinValuesStmts, '%s.sql' % coinValues['tableName'], UsCoinDB)
coinValueMapping = createMapping(coinValues, 'name', 'cvid')

# Coin types (Barber, Liberty Walking, etc)
coins = fileToRecord('%s/%s/coin.txt' % (DB_DIR,UsCoinDB))
coinStmts = recordToSQL(coins, [{'mapping':coinValueMapping, 'field':'type'}])
writeStatements(coinStmts, '%s.sql' % coins['tableName'], UsCoinDB)
coinDenominationMapping = createMultiKeyMapping(coins, ['name', 'type'], 'cid')

print coinDenominationMapping

# Coin Value Attribs
coinValueAttribs = fileToRecord('%s/%s/coin_Value_Attrib.txt' % (DB_DIR,UsCoinDB))
coinValueAttribsStmts = recordToSQL(coinValueAttribs, [{'mapping':coinValueMapping, 'field':'name'}])
writeStatements(coinValueAttribsStmts, '%s.sql' % coinValueAttribs['tableName'], UsCoinDB)

# Coin Attribs
coinAttribs = fileToRecord('%s/%s/coin_Attrib.txt' % (DB_DIR,UsCoinDB))
coinAttribsStmts = recordToSQL(coinAttribs, [{'mapping':coinDenominationMapping, 'field':'name||type'}], ['type'])
writeStatements(coinAttribsStmts, '%s.sql' % coinAttribs['tableName'], UsCoinDB)

# Philadelphia, San Francisco, Denver, etc.
mints = fileToRecord('%s/%s/mint.txt' % (DB_DIR,UsCoinDB))
mintStmts = recordToSQL(mints)
writeStatements(mintStmts, '%s.sql' % mints['tableName'], UsCoinDB)
mintMapping = createMapping(mints, 'mint', 'mid')
mintMarkMapping = createMapping(mints, 'symbol', 'mid')

# Dates mints were in operation
mintDates = fileToRecord('%s/%s/mint_Date.txt' % (DB_DIR,UsCoinDB))
mintDatesStmts = recordToSQL(mintDates, [{'mapping':mintMapping, 'field':'mint'}])
writeStatements(mintDatesStmts, '%s.sql' % mintDates['tableName'], UsCoinDB)

# Information about a coin made in a specific year
coinYears = fileToRecord('%s/%s/coin_Year.txt' % (DB_DIR,UsCoinDB))
coinYearsStmts = recordToSQL(coinYears, 
	[{'mapping':coinDenominationMapping, 'field':'coin||denomination'}],
        ['denomination'])
writeStatements(coinYearsStmts, '%s.sql' % coinYears['tableName'], UsCoinDB)
coinYearMapping = createSpecialMapping(coinYears, coinDenominationMapping, 'coin||denomination', 'year||additionalInfo', 'cyid')

# Coin condition ranking (G-MS 4-70) information
#coinConditions = fileToRecord('%s/%s/coin_Condition.txt' % (DB_DIR,UsCoinDB))
#coinConditionsStmts = recordToSQL(coinConditions)
#writeStatements(coinConditionsStmts, '%s.sql' % coinConditions['tableName'],  UsCoinDB)
#coinConditionMapping = createMapping(coinConditions, 'title', 'ccid')

# Coin grade categories 
#coinGradeCategories = fileToRecord('%s/%s/coin_Grade_Category.txt' % (DB_DIR,UsCoinDB))
#coinGradeCategoriesStmts = recordToSQL(coinGradeCategories)
#writeStatements(coinGradeCategoriesStmts, '%s.sql' % coinGradeCategories['tableName'], UsCoinDB)
#print coinGradeCategories
#coinGradeCategoriesMapping = createMapping(coinGradeCategories, 'category', 'cgcid')

# Coin grades
#coinGrades = fileToRecord('%s/%s/coin_Grade.txt' % (DB_DIR,UsCoinDB))
#coinGradesStmts = recordToSQL(coinGrades, [{'mapping':coinGradeCategoriesMapping, 'field':'category'}])
#writeStatements(coinGradesStmts, '%s.sql' % coinGrades['tableName'], UsCoinDB)
#print coinGrades

# Specific coin based on a mint and year
mintCoins = {}

def coinTypeFromPath(path, coin, mapping):
  denomination = path.replace('%s/' % MINT_COIN_PATH, '')
  key = '%s||%s' % (coin, denomination)

  return mapping[key]
  
maxId = 0
for (path, dirs, files) in os.walk(MINT_COIN_PATH):
  for coin in files:
    coinWithoutExt = coin.replace('.txt','')
    cvid = coinTypeFromPath(path, coinWithoutExt, coinDenominationMapping)
    if not mintCoins.has_key(cvid):
      mintCoins[cvid] = []

    print 'getting %s/%s' % (path, coin)
    coinData = fileToRecord('%s/%s' % (path, coin), 'MintCoin', 'mcid')

    maxData = convertIdsForMax(coinData, maxId)    
    coinData = maxData[0]
    maxId = maxData[1]    

    mintCoins[cvid] = coinData



keys = mintCoins.keys()
keys.sort()
for k in keys:
  print '%s: %s' % (k, mintCoins[k])
  
print coinYearMapping
mintCoinStatements = createMintCoinSQL(mintCoins, mintMapping, coinYearMapping)
writeStatements(mintCoinStatements, '%s.sql' % 'MintCoin', UsCoinDB)

# Different values for a coin based on condition
mintCoinValueStatements = createMintCoinValueSQL(mintCoins, sheldonRatingScaleMapping) #coinConditionMapping)
writeStatements(mintCoinValueStatements, '%s.sql' % 'MintCoinValue', UsCoinDB)
