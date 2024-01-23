'use client'

import { FileImportType, FileImportStatusType } from "@/types/FileImportType"
import { ClockIcon, SymbolIcon, CheckCircledIcon, CrossCircledIcon, InfoCircledIcon } from "@radix-ui/react-icons"
import { ColumnDef } from "@tanstack/react-table"

export const columns: ColumnDef<FileImportType>[] = [
  {
    accessorKey: 'id',
    header: '#'
  },
  {
    accessorKey: 'filename',
    header: 'Filename'
  },
  {
    accessorKey: 'status',
    header: 'Status',
    cell: ({ row }) => {
      const status = row.getValue<FileImportStatusType>('status')
      const capitalizedStatus = status.slice(0, 1) + status.slice(1).toLowerCase()

      let icon = <ClockIcon className="text-gray-500 mr-2 scale-125" />

      if (status === 'PROCESSING') {
        icon = <SymbolIcon className="text-blue-500 animate-spin spin-in-180 mr-2" />
      } else if (status === 'SUCCESS') {
        icon = <CheckCircledIcon className="text-green-500 scale-125 mr-2" />
      } else if (status === 'ERROR') {
        icon = <CrossCircledIcon className="text-red-500 scale-125 mr-2" />
      } else if (status === 'WARNING') {
        icon = <InfoCircledIcon className="text-orange-500 scale-125 mr-2" />
      }

      return (
        <div className="flex items-center">
          {icon}
          {capitalizedStatus}
        </div>
      )
    }
  },
  {
    accessorKey: 'created_at',
    header: 'Uploaded at',
    cell: ({ row }) => {
      const formatted = new Date(Date.parse(row.getValue('created_at')))
      
      return formatted.toLocaleDateString('pt-br', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      })
    }
  },
]