'use client'

import { Table } from "@/components/DataTable";
import { columns } from "./FileImportErrorTableColumns";
import { ResponseType } from "@/types/ResponseType";
import { FileImportErrorType } from "@/types/FileImportErrorType";
import { useCallback, useEffect, useState } from "react";

type NotificationTableProps = {
  fileImportId?: number
}

export default function FileImportErrorsTable({ fileImportId }: NotificationTableProps) {
  const [response, setResponse] = useState<ResponseType<FileImportErrorType>>()

  const fetchFiles = useCallback(async (page = 1) => {
    const dataRaw = await fetch(`http://localhost:8000/api/files/${fileImportId}/errors?page=${page}`);
    const data = (await dataRaw.json()) as ResponseType<FileImportErrorType>

    setResponse(data)
  }, [fileImportId])

  useEffect(() => {
    fetchFiles()
  }, [fetchFiles])

  if (!response) {
    return 'Loading zxcv'
  }

  return <Table title="Errors" columns={columns} {...response} onRefresh={fetchFiles}/>
}