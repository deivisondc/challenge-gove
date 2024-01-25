'use client'

import { Table } from "@/components/DataTable";
import { getColumns } from "./NotificationsTableColumns";
import { ResponseType } from "@/types/ResponseType";
import { NotificationsType } from "@/types/NotificationsType";
import { useCallback, useEffect, useState } from "react";
import { NotificationsTableFilter } from "./NotificationsTableFilter";
import { apiFetch } from "@/service/api";
import { ExceptionBoundary } from "@/components/ExceptionBoundary";
import { TableSkeleton } from "@/components/DataTable/Skeleton";

type NotificationTableProps = {
  fileImportId?: number
}

export default function NotificationsTable({ fileImportId }: NotificationTableProps) {
  const [response, setResponse] = useState<ResponseType<NotificationsType>>()
  const [error, setError] = useState('')
  const [filterQueryParams, setFilterQueryParams] = useState('');
  
  const fetchNotifications = useCallback(async (page = 1) => {
    try {
      const data = await apiFetch<ResponseType<NotificationsType>>({
        resource: `/files/${fileImportId}/notifications`,
        queryParams: `page=${page}${filterQueryParams}`
      })

      setResponse(data)
      setError('')
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message)
      }
    }
  }, [fileImportId, filterQueryParams])

  useEffect(() => {
    fetchNotifications()
  }, [fetchNotifications])

  const columns = getColumns({ fetchNotifications })

  return (
    <ExceptionBoundary error={error} asChild>
      {response ? (
        <Table
          title="Notifications"
          columns={columns}
          onRefresh={fetchNotifications}
          filterComponent={<NotificationsTableFilter setFilter={setFilterQueryParams} />}
          {...response}
        />
      ) : <TableSkeleton hasFilter error={error} />}
    </ExceptionBoundary>
  )
}