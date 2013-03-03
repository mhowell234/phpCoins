
import os

BASE_DIR = "/Users/mhowell/Dropbox/numismatics"
DB_DIR = "%s/DB" % BASE_DIR
LINE_SEPARATOR = "^^^^"

UsCoinDB = 'UsCoinDB'
CommonDB = 'CommonDB'
ForeignCoinDB = 'ForeignCoinDB'
OurCoinDB = 'OurCoinDB'
TrackerDB = 'TrackerDB'

#
# Replace HTML BR's with new lines
#
def formatFile(fName):
  f = open(fName, 'r')

  data = f.read()
  f.close()

  lines = data.split('<br />')

  f = open(fName, 'w')

  for line in lines:
    f.write('%s\n' % line)

  f.close()


#
# Makes sure a directory exists for a f path
#
def ensure_dir(f):
    d = os.path.dirname(f)
    if not os.path.exists(d):
        os.makedirs(d)


#
# Converts a f token to a field name
# tokens are split by _ and results in a camelCase name
#
def convertTokenToFieldName(token):
  # camelCase
  first = True
  
  parts = token.split('_')
  data = []
  for part in parts:
    part = part.strip()
    if first:
      data.append(part.lower())
      first = False
      continue
      
    data.append(part.lower().capitalize())
    
  return ''.join(data) 


#
# Converts a DB f id into a SQL primary key
# f id is split by _ and results in a lowercase primary key id
#
def convertFileNameToTableId(token):
  # first letter of each part of the table + 'id' 
  token = token.replace('.txt', '')


  token = token.split('/')[-1] 
   
  parts = token.split('_')
  
  data = []
  
  for part in parts:
    part = part.lower().strip()
    data.append(part[0])

  data.append('id') 
     
  return ''.join(data) 


#
# Converts a DB f name into a SQL table name
# f name is split by _ and results in a camelCase table name
#
def convertFileNameToTableName(token):
  token = token.replace('.txt', '')
  
  
  token = token.split('/')[-1]

  parts = token.split('_')

  data = []

  for part in parts:
    part = part.strip()
    part = part.lower().capitalize()
    data.append(part)

  return ''.join(data)  
   

#
# Determines if a column should be treated as a different type other than a string
# Based on column name
#
def convertFieldsToColumns(fields, table, specialCol=None, specialColType=None):
  columns = []
  colType = 'str' 
 
  for field in fields:
    colType = 'str'

    if table == 'CoinValue':
      if field in ['value','year']:
        colType = 'int'
    elif table == 'CoinValueAttrib':
      if field in ['name', 'attribType']:
        colType = 'int'
    elif table == 'Coin':
      if field in ['startYear', 'endYear', 'type']:
        colType = 'int'
    elif table == 'CoinAttrib':
      if field in ['name', 'attribType']:
        colType = 'int'
    elif table == 'Mint':
      if field in ['alwaysPresent']:
        colType = 'int'
    elif table == 'MintDate':
      if field in ['mint', 'startYear', 'endYear']:
        colType = 'int'
    elif table == 'CoinYear':
      if field in ['coin', 'year', 'isGold', 'isSilver']:
        colType = 'int'
    elif table == 'CoinCondition':
      if field in ['value', 'specialOrder']:
        colType = 'int'
    elif table == 'MintCoin':
      if field in ['g-4', 'vg-8', 'f-12', 'vf-20', 'ef-40', 'au-50', 'ms-60', 'ms-63', 'pf-63', 'mintage', 'paren']:
        colType = 'int'
    elif table == 'SheldonRatingScale':
      if field in ['value', 'category']:
        colType = 'int'
    elif table == 'SheldonRatingCategory':    
      if field in ['startValue','endValue','altType']:
        colType = 'int'
    elif table == 'PreciousMetal':
      if field in ['conversionFactor']:
        colType = 'int'
    elif table == 'EmailAddress':
      if field in ['esid']:
        colType = 'int'
    elif table == 'TrackerSearch':
      if field in ['value', 'cvid','cid','coin','cyid','year','cyidStart','startYear','cyidEnd','endYear','mcid','mid','year','minPrice','maxPrice','discountPercentage','premiumPercentage','auctionEndTime','sellerRating','isBuyItNow']:
        colType = 'int'

    columns.append({'column':field, 'type':colType})

  if specialCol:
    columns.append({'column':specialCol, 'type':specialColType})

  return columns


#
# Converts a DB f to a list of dict objects with metadata
#
def fileToRecord(fName, tableName=None, tableId=None):

  if not tableName: tableName = convertFileNameToTableName(fName)
  if not tableId: tableId = convertFileNameToTableId(fName)

  output = {'data': [], 'tableName': tableName, 'tableId': tableId, 'columns':[]}

  fields = []
  fieldLine = True
  idNum = 1
  
  f = open(fName, 'r')
  
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
      output['columns'] = convertFieldsToColumns(fields, tableName)
      continue 
    
    record = {}
    record[tableId] = idNum

    print line
    tokenId = 0
    for token in tokens:
      fieldName = fields[tokenId]

      token = token.replace(LINE_SEPARATOR, '\n\n')
      
      if fieldName == 'year':
        token = token.replace(':', ' ')
      record[fieldName] = token
      tokenId = tokenId + 1
  
    idNum = idNum + 1
    output['data'].append(record)
  
  f.close()
 
  return output


#
# Creates a dict mapping using the key and value as input
#
def createMapping(tableData, key, value):
  mapping = {}

  data = tableData['data']

  for record in data:
    keyVal = record[key]
    valVal = record[value]

    mapping[keyVal] = valVal

  return mapping


# 
# Creates a mapping using an existing mapping and a multi-key
#
def createSpecialMapping(tableData, existingMapping, multiPartKey, newKeyName, value):
  mapping = {}
  data = tableData['data']

  keys = []
  tokens = multiPartKey.split('||')
  for key in tokens: keys.append(key)

  for record in data:
    compKey = []

    for key in keys:
      keyVal = record[key]
      compKey.append(keyVal)
    valVal = record[value]
    compKeyStr = '||'.join(compKey)
    newKey = existingMapping[compKeyStr]
    ntokens = newKeyName.split('||')
    for ntoken in ntokens:
      newKey = '%s||%s' % (newKey, record[ntoken])

    #newKey = '%s||%s' % (newKey, record[newKeyName])
    mapping[newKey] = valVal

  print mapping
  return mapping  


#
# Creates a multi-key mapping based on the list of keys and mapped to a value
#
def createMultiKeyMapping(tableData, keys, value):
  mapping = {}
  data = tableData['data']

  for record in data:
    compKey = []
    for key in keys:
      keyVal = record[key]
      compKey.append(keyVal)
    valVal = record[value]

    compKeyStr = '||'.join(compKey)
    mapping[compKeyStr] = valVal

  return mapping


#
# Convert a dict objects into an SQL insert statements
# Mapping obj is used to convert text column into an id using a lookup
#
def recordToSQL(data, mapping=None, fieldsToSkip=None, fieldsToAdd=None):
  stmts = []

  BASE_SQL = 'INSERT INTO %s VALUES(' % data['tableName']
  columns = data['columns']
  tid = data['tableId']

  records = data['data']
  for record in records:
    sql = [BASE_SQL]
    sql.append('%s' % record[tid])

    for column in columns:
      colName = column['column']
      colType = column['type']

      if fieldsToSkip and colName in fieldsToSkip: continue

      auxField = None

      if mapping:
        for mapType in mapping:
          mappingData = mapType['mapping']
          field = mapType['field']
          print mapType
          tokens = field.split('||')
          field = tokens[0]
          if (len(tokens) > 1):
            auxField = tokens[1]

          if field == colName:
            print 'Looking up column %s' % field
            print 'in %s' % record
            compKey = record[field]
            if auxField:
              compKey = '%s||%s' % (compKey, record[auxField])

            print 'MD::', mappingData
            colValue = mappingData[compKey]

          else: colValue = record[colName]
      else:
        colValue = record[colName]
 
      if colType == 'int':
        if colValue in [None, '']:
          sql.append(', NULL')
        else:
          sql.append(', %s' % colValue)
      elif colType == 'str':
        sql.append(', "%s"' % colValue)

    if fieldsToAdd:
      for field in fieldsToAdd:
        if field['type'] == 'int':
          sql.append(', %s' % field['field'])
        else:
          sql.append(', "%s"' % field['field'])
          
    sql.append(');')
    stmts.append(''.join(sql))      
  
  return stmts 


#
# Write statements from a list to a f
#
def writeStatements(stmts, fName, DBName):
  DIR = '%s/sqlImport' % BASE_DIR

  fPath = '%s/%s/%s' % (DIR, DBName, fName)
  ensure_dir(fPath)

  print 'OPENING...%s' % (fPath)
  f = open(fPath, 'a')

  if DBName not in ['..', '.']:
    f.write('-- Start of using %s\n' % DBName)
    f.write('USE %s;\n\n' % DBName)
   
  for stmt in stmts:
    f.write('%s\n\n' % stmt)

  f.close()


#
# Convert coin ids to a new increment based on a start id (maxId)
#
def convertIdsForMax(coinData, maxId):
  info = coinData['data']

  newData = []

  for data in info:
    maxId = maxId + 1
    data['mcid'] = maxId    
    newData.append(data)

  coinData['data'] = newData
  return [coinData, maxId]


#
# Add schema statements for a file path
#
def addSchemaData(path):
  print 'in schema data'
  stmts = []
  
  PATH = path
  print PATH
  for (path, dirs, files) in os.walk(PATH):
    
    for fileName in files:
      print fileName, dirs
      f = open('%s/%s' % (path, fileName), 'r')
    
      data = f.read()      
      stmts.append(data)
      
      f.close()
      
  return stmts


#
# Changes the defined type for a column in record data
#
def changeColumnType(record, column, type):
  columns = record['columns']
  for columnData in columns:
    if columnData['column'] == column:
      columnData['type'] = type


#
# Tokenize reference line
#
def tokenizeReferenceLine(line):

  line = line.replace('\n','').strip()
  if line in [None, '']: return None

  xline = line.replace(' ', '')
  if xline[0] == '#': return None

  tokens = line.split('|')

  return tokens


#
# Creates reference key->val pair
#
def buildKeyValRef(keyList, val):

  #print 'in BUILD-KEY-VAL-%s--%s' % (keyList, val)
  keys = []
  for k in keyList:
    #if k in [None]: continue
    
    k = k.strip()
    
    #if k in [None, '']: continue
    
    keys.append(k)

  #if keys[-1] == '':
  #  print 'NONO: %s-%s' % (keyList, val)
  key = '||'.join(keys)
  key = key.lower()
  val = val.strip()
  
  #print 'OUT-%s-----%s' % (key, val)
  return (key, val)
  

#
# Gets rating categories reference
#
def getRatingCategoryReference():
  f = open('%s/data/ratingCategoryReference' % BASE_DIR, 'r')
  ref = {}
  
  for line in f:
    tokens = tokenizeReferenceLine(line)
    if not tokens:
      continue
      

    keyList = [tokens[0]]
      
    (key, value) = buildKeyValRef(keyList, tokens[3])
    
    ref[key] = value

  f.close()
  
  return ref


#
# Gets rating values reference
#
def getRatingValueReference():
  f = open('%s/data/ratingValueReference' % BASE_DIR, 'r')
  ref = {}
  
  for line in f:
    tokens = tokenizeReferenceLine(line)
    if not tokens:
      continue
      

    keyList = [tokens[0]]
      
    (key, value) = buildKeyValRef(keyList, tokens[2])
    
    ref[key] = value

  f.close()
  
  return ref

  
#
# Gets coin value reference
#
def getCoinValueReference():
  f = open('%s/data/coinValueReference' % BASE_DIR, 'r')
  ref = {}
  
  for line in f:
    tokens = tokenizeReferenceLine(line)
    if not tokens:
      continue
      
    keyList = [tokens[0]]
      
    (key, value) = buildKeyValRef(keyList, tokens[1])
    
    ref[key] = value

  f.close()
  
  return ref


#
# Gets coin reference
#
def getCoinReference():
  f = open('%s/data/coinReference' % BASE_DIR, 'r')
  ref = {}
  
  for line in f:
    tokens = tokenizeReferenceLine(line)
    if not tokens:
      continue
      

    keyList = [tokens[0], tokens[1]]
      
    (key, value) = buildKeyValRef(keyList, tokens[2])
    
    ref[key] = value

  f.close()
  
  return ref
  

#
# Gets coin year reference
#
def getCoinYearReference():
  f = open('%s/data/coinYearReference' % BASE_DIR, 'r')
  ref = {}
  
  for line in f:
    tokens = tokenizeReferenceLine(line)
    if not tokens:
      continue
      
    keyList = [tokens[0], tokens[1], tokens[2], tokens[3]]
      
    (key, value) = buildKeyValRef(keyList, tokens[4])
    
    ref[key] = value

  f.close()
  
  return ref


#
# Gets mint reference
#
def getMintReference():
  f = open('%s/data/mintReference' % BASE_DIR, 'r')
  ref = {}
  
  for line in f:
    tokens = tokenizeReferenceLine(line)
    if not tokens:
      continue
      
    keyList = [tokens[0]]
      
    (key, value) = buildKeyValRef(keyList, tokens[4])
    
    ref[key] = value

  f.close()
  
  return ref


#
# Gets a mint name -> symbol mapping reference
#
def getMintSymbolReference():
  f = open('%s/data/mintReference' % BASE_DIR, 'r')
  ref = {}
  
  for line in f:
    tokens = tokenizeReferenceLine(line)
    if not tokens:
      continue
      
    keyList = [tokens[0]]
      
    (key, value) = buildKeyValRef(keyList, tokens[1])
    
    ref[key] = value

  f.close()
  
  return ref

#
# Gets mint coin reference
#
def getMintCoinReference():
  f = open('%s/data/mintCoinReference' % BASE_DIR, 'r')
  ref = {}
  
  for line in f:
    tokens = tokenizeReferenceLine(line)
    if not tokens:
      continue
      
    keyList = [tokens[0], tokens[1], tokens[2], tokens[3], tokens[4], tokens[5]]
      
    (key, value) = buildKeyValRef(keyList, tokens[6])
    
    ref[key] = value

  f.close()
  
  return ref


#
# Looks up a reference value
#
def lookupReferenceValue(input, ref):
    
    key = '||'.join(input)
    key = key.lower()

    print 'looking up --%s-- in ref..' %(key)
    
    keys = ref.keys()
    keys.sort()
    for k in keys:
      print '--%s--\n' % k
    
    print '\n'
    print '\n'
    print '\n'
    
    if (not ref.has_key(key)):
       print 'no value...'
       return None
    
    print 'val is %s' % ref[key] 
    return ref[key]