'use client'

import { Table } from "@/components/DataTable";
import { columns } from "./columns";
import { FileImportType } from "@/types/FileImportType";
import { ResponseType } from "@/types/ResponseType";

type FileImportsTableProps = {
  onRowClick: (itemId: number) => void
  fetchFiles: () => void
} & ResponseType<FileImportType>

export default function FileImportsTable({ onRowClick, fetchFiles, ...props }: FileImportsTableProps) {  
  return (
    <Table
      description="Select a file to see more details."
      columns={columns}
      onRowClick={onRowClick}
      onRefresh={fetchFiles}
      {...props}
    />
  )
}