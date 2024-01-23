'use client'

import { FileImportErrorType } from "@/types/FileImportErrorType"
import { ColumnDef } from "@tanstack/react-table"

export const columns: ColumnDef<FileImportErrorType>[] = [
  {
    accessorKey: 'error',
    header: 'Description'
  },
]