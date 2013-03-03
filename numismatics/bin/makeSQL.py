import os
from utils import BASE_DIR, writeStatements, addSchemaData

SCHEMA_BASE = '%s/sqlImport' % BASE_DIR

CommonDBPath = '%s/CommonDB' % (SCHEMA_BASE)
UsCoinDBPath = '%s/UsCoinDB' % (SCHEMA_BASE)

stmts = []


# Create CommonDB
commonStmts = addSchemaData(CommonDBPath)

# Create UsCoinDB
UsCoinStmts = addSchemaData(UsCoinDBPath)

for s in [commonStmts, UsCoinStmts]:
  for t in s:
    stmts.append(t)
    
 
writeStatements(stmts, 'SQL.sql', '..')
