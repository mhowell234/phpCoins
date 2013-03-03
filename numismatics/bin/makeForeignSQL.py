import os
from utils import BASE_DIR, ForeignCoinDB, writeStatements, addSchemaData

SCHEMA_BASE = 'sqlImport'

ForeignCoinDBPath = '%s/%s/%s' % (BASE_DIR, SCHEMA_BASE, ForeignCoinDB)

stmts = []


# Create ForeignCoinDB
ForeignCoinStmts = addSchemaData(ForeignCoinDBPath)

for s in [ForeignCoinStmts]:
  for t in s:
    stmts.append(t)
    
 
writeStatements(stmts, 'FOREIGN_SCHEMA.sql', '..')
