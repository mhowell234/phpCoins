import os
from utils import BASE_DIR, writeStatements, addSchemaData

SCHEMA_BASE = '%s/schema' % BASE_DIR

CommonDBPath = '%s/CommonDB' % (SCHEMA_BASE)
UsCoinDBPath = '%s/UsCoinDB' % (SCHEMA_BASE)
ForeignCoinDBPath = '%s/ForeignCoinDB' % (SCHEMA_BASE)
OurCoinDBPath = '%s/OurCoinDB' % (SCHEMA_BASE)
OurForeignCoinDBPath = '%s/OurForeignCoinDB' % (SCHEMA_BASE)
SearchDBPath = '%s/SearchDB' % (SCHEMA_BASE)
TrackerDBPath = '%s/TrackerDB' % (SCHEMA_BASE)

stmts = []


# Drop DBs
f = open('%s/%s' % (SCHEMA_BASE, 'AAAA-DB_SCHEMA.sql'), 'r')
for line in f:
  stmts.append(line)
f.close()


# Create CommonDB
commonStmts = addSchemaData(CommonDBPath)

# Create UsCoinDB
UsCoinStmts = addSchemaData(UsCoinDBPath)

# Create ForeignCoinDB
ForeignCoinStmts = addSchemaData(ForeignCoinDBPath)

# Create OurCoinDB
OurCoinStmts = addSchemaData(OurCoinDBPath)

# Create OurForeignCoinDB
OurForeignCoinStmts = addSchemaData(OurForeignCoinDBPath)

# Create SearchDB
SearchStmts = addSchemaData(SearchDBPath)

# Create EbayDB
TrackerStmts = addSchemaData(TrackerDBPath)



for s in [commonStmts, UsCoinStmts, ForeignCoinStmts, OurCoinStmts, OurForeignCoinStmts, SearchStmts, TrackerStmts]:
  for t in s:
    stmts.append(t)
    

writeStatements(stmts, 'SCHEMA.sql', '..')
