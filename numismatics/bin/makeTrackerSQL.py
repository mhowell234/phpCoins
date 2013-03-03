import os
from utils import BASE_DIR, TrackerDB, writeStatements, addSchemaData

SCHEMA_BASE = 'sqlImport'

TrackerDBPath = '%s/%s/%s' % (BASE_DIR, SCHEMA_BASE, TrackerDB)

stmts = []


# Create TrackerDBPath
TrackerStmts = addSchemaData(TrackerDBPath)

for s in [TrackerStmts]:
  for t in s:
    stmts.append(t)
    
 
writeStatements(stmts, 'TRACKER_SCHEMA.sql', '..')
