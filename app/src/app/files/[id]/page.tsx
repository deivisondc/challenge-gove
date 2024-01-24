'use client'

import { PageTitle } from "@/components/PageTitle";
import { FileImportType } from "@/types/FileImportType";
import NotificationsTable from "./components/Notifications/NotificationsTable";
import FileImportErrorsTable from "./components/FileImportErrors/FileImportErrorTable";
import StatusCard from "./components/StatusCard";
import { format } from "date-fns";
import { ExceptionBoundary } from "@/components/ExceptionBoundary";
import { apiFetch } from "@/service/api";
import { useCallback, useEffect, useState } from "react";
import { Skeleton } from "@/components/ui/skeleton";

type FileImportDetailsProps = {
  params: {
    id: number
  }
}

export const dynamic = 'force-dynamic'
export const revalidate = 0

export default function FileImportDetails({ params }: FileImportDetailsProps) {
  const [response, setResponse] = useState<FileImportType>();
  const [error, setError] = useState('');
  
  const fetchFileData = useCallback(async (page = 1) => {
    try {
      const data = await apiFetch<FileImportType>({
        resource: `/files/${params.id}`
      })

      setResponse(data)
      setError('')
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message)
      }
    }
  }, [params.id])

  useEffect(() => {
    fetchFileData()
  }, [fetchFileData])

  return (
    <>
      <div className="flex gap-2 items-center">
        <PageTitle backButtonHref="/files">
          Files
        </PageTitle>
      </div>

      <ExceptionBoundary error={error}>
        <>
          <p className="mt-1 text-gray-500 text-sm">Filename: {!response ? '...' : `${response.filename} - ${format(response.created_at, 'yyyy-MM-dd - HH:mm:ss')}`}</p>
          {response ? (
            <>
              <StatusCard status={response.status} />
              
            </>
            ) : <Skeleton className="w-[400px] h-[78px] my-2 p-4 rounded-lg" />}
        </>
      </ExceptionBoundary>

      <NotificationsTable fileImportId={params.id} />
      <FileImportErrorsTable fileImportId={params.id} />

    </>
  )
}