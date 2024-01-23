'use client'

import { Table } from "@/components/DataTable";
import { columns } from "./columns";
import { FileImportType } from "@/types/FileImportType";
import { ResponseType } from "@/types/ResponseType";

type FileImportsTableProps = {
  fetchFiles: () => void
} & ResponseType<FileImportType>

export default function FileImportsTable({ fetchFiles, ...props }: FileImportsTableProps) {  
  return (
    <Table
      columns={columns}
      onRefresh={fetchFiles}
      {...props}
    />
  )
}