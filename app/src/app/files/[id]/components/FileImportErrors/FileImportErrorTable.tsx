'use client'

import { Table } from "@/components/DataTable";
import { columns } from "./FileImportErrorTableColumns";
import { ResponseType } from "@/types/ResponseType";
import { FileImportErrorType } from "@/types/FileImportErrorType";
import { useCallback, useEffect, useState } from "react";
import { apiFetch } from "@/service/api";
import { ExceptionBoundary } from "@/components/ExceptionBoundary";
import { TableSkeleton } from "@/components/DataTable/Skeleton";

type NotificationTableProps = {
  fileImportId?: number
}

export default function FileImportErrorsTable({ fileImportId }: NotificationTableProps) {
  const [response, setResponse] = useState<ResponseType<FileImportErrorType>>()
  const [error, setError] = useState('');

  const fetchFiles = useCallback(async (page = 1) => {
    try {
      const data = await apiFetch<ResponseType<FileImportErrorType>>({
        resource: `/files/${fileImportId}/errors`,
        queryParams: `page=${page}`
      })

      setResponse(data)
      setError('')
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message)
      }
    }
  }, [fileImportId])

  useEffect(() => {
    fetchFiles()
  }, [fetchFiles])

  return (
    <ExceptionBoundary error={error}>
      {response ? (
        <Table title="Errors" columns={columns} {...response} onRefresh={fetchFiles}/>
      ) : <TableSkeleton error={error} />}
    </ExceptionBoundary>
  )
}