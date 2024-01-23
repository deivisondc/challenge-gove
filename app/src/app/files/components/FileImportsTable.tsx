'use client'

import { Table } from "@/components/DataTable";
import { columns } from "./columns";
import { FileImportType } from "@/types/FileImportType";
import { ResponseType } from "@/types/ResponseType";

type FileImportsTableProps = ResponseType<FileImportType>

export default function FileImportsTable(props: FileImportsTableProps) {  
  return (
    <Table
      columns={columns}
      {...props}
    />
  )
}