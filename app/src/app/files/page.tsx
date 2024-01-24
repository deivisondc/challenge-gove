'use client'

import { PageTitle } from "@/components/PageTitle";
import { UploadButton } from "./components/UploadButton";
import { TemplateFileButton } from "./components/TemplateFileButton";
import FileImportsTable from "./components/FileImportsTable";
import { useCallback, useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { ResponseType } from "@/types/ResponseType";
import { FileImportType } from "@/types/FileImportType";
import { apiFetch } from "@/service/api";
import { ExceptionBoundary } from "@/components/ExceptionBoundary";
import { TableSkeleton } from "@/components/DataTable/Skeleton";

export default function Files() {
  const { push } = useRouter()
  const [response, setResponse] = useState<ResponseType<FileImportType>>()
  const [error, setError] = useState('')

  const fetchFiles = useCallback(async (page = 1) => {
    try {
      const data = await apiFetch<ResponseType<FileImportType>>({
        resource: '/files',
        queryParams: `page=${page}`
      })

      setResponse(data)
      setError('')
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message)
      }
    }
  }, [])

  useEffect(() => {
    fetchFiles()
  }, [fetchFiles])


  function onRowClick(itemId: number) {
    push(`/files/${itemId}`)
  }

  return (
    <>
      <div className="flex justify-between items-start flex-col sm:flex-row gap-2">
        <PageTitle>Files</PageTitle>

        <div className="flex flex-row-reverse sm:flex-row items-center gap-4">
          <TemplateFileButton />
          <UploadButton onSuccess={fetchFiles} />
        </div>
      </div>

      <ExceptionBoundary error={error} asChild>
        {response ? (
          <FileImportsTable
            onRowClick={onRowClick}
            fetchFiles={fetchFiles}
            {...response}
          />
        ) : <TableSkeleton hasDescription error={error} />} 
      </ExceptionBoundary>

    </>
  );
};