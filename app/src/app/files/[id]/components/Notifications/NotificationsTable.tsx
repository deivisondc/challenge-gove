'use client'

import { Table } from "@/components/DataTable";
import { columns } from "./NotificationsTableColumns";
import { ResponseType } from "@/types/ResponseType";
import { NotificationsType } from "@/types/NotificationsType";
import { useCallback, useEffect, useState } from "react";
import { NotificationsTableFilter } from "./NotificationsTableFilter";

type NotificationTableProps = {
  fileImportId?: number
}

export default function NotificationsTable({ fileImportId }: NotificationTableProps) {
  const [response, setResponse] = useState<ResponseType<NotificationsType>>()
  const [filterQueryParams, setFilterQueryParams] = useState('');
  
  const fetchNotifications = useCallback(async (page = 1) => {
    const dataRaw = await fetch(`http://localhost:8000/api/files/${fileImportId}/notifications?page=${page}${filterQueryParams}`, {
      cache: 'no-cache'
    });
    const data = (await dataRaw.json()) as ResponseType<NotificationsType>

    setResponse(data)
  }, [fileImportId, filterQueryParams])

  useEffect(() => {
    fetchNotifications()
  }, [fetchNotifications])

  if (!response) {
    return 'Loading'
  }

  return <Table
  	title="Notifications"
  	columns={columns}
  	onRefresh={fetchNotifications}
    filterComponent={<NotificationsTableFilter setFilter={setFilterQueryParams} />}
  	{...response}
  />
}