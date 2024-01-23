'use client'

import { NotificationsStatusType, NotificationsType } from "@/types/NotificationsType"
import { ClockIcon, SymbolIcon, CheckCircledIcon, CrossCircledIcon, DotsVerticalIcon, DividerHorizontalIcon } from "@radix-ui/react-icons"
import { ColumnDef } from "@tanstack/react-table"

import { NotificationsTableActions } from "./NotificationsTableActions"
import { Button } from "@/components/ui/button"
import { cn } from "@/lib/utils"

type ColumnProps = {
  fetchNotifications: () => Promise<void>
}

export const getColumns = ({ fetchNotifications }: ColumnProps): ColumnDef<NotificationsType>[] => [
  {
    id: 'contact.name',
    accessorKey: 'contact.name',
    header: 'Name',
    cell: ({ row }) => (
      <span className={cn({
        'opacity-50': row.getValue<NotificationsStatusType>('status') === 'CANCELED'
      })}>
        {row.getValue<NotificationsStatusType>('contact.name')}
      </span>
    )
  },
  {
    id: 'contact.contact',
    accessorKey: 'contact.contact',
    header: 'Contact',
    cell: ({ row }) => (
      <span className={cn({
        'opacity-50': row.getValue<NotificationsStatusType>('status') === 'CANCELED'
      })}>
        {row.getValue<NotificationsStatusType>('contact.contact')}
      </span>
    )
  },
  {
    accessorKey: 'status',
    header: 'Status',
    cell: ({ row }) => {
      const status = row.getValue<NotificationsStatusType>('status')
      const capitalizedStatus = status.slice(0, 1) + status.slice(1).toLowerCase()

      let icon = <ClockIcon className="text-gray-500 mr-2 scale-125" />

      if (status === 'QUEUED') {
        icon = <SymbolIcon className="text-blue-500 animate-spin spin-in-180 mr-2" />
      } else if (status === 'SUCCESS') {
        icon = <CheckCircledIcon className="text-green-500 scale-125 mr-2" />
      } else if (status === 'ERROR') {
        icon = <CrossCircledIcon className="text-red-500 scale-125 mr-2" />
      } else if (status === 'CANCELED') {
        icon = <DividerHorizontalIcon className="text-gray-500 scale-125 mr-2" />
      }

      return (
        <div className={cn("flex items-center", {
          'opacity-50': row.getValue<NotificationsStatusType>('status') === 'CANCELED'
        })}>
          {icon}
          <span className="hidden md:block">{capitalizedStatus}</span>
        </div>
      )
    }
  },
  {
    accessorKey: 'scheduled_for',
    header: 'Scheduled for',
    cell: ({ row }) => (
      <span className={cn({
        'opacity-50': row.getValue<NotificationsStatusType>('status') === 'CANCELED'
      })}>
        {row.getValue<NotificationsStatusType>('scheduled_for')}
      </span>
    )
  },
  {
    id: "actions",
    cell: ({ row }) => {
      const notification = row.original
      
      async function onRetry() {
        await fetch(`http://localhost:8000/api/notifications/${notification.id}/retry`, {
          method: 'PUT'
        })

        fetchNotifications()
      }

      return <NotificationsTableActions notification={notification} onRetry={onRetry} onEdit={fetchNotifications} />
      // return <Button variant="outline" size="sm">
      //   <DotsVerticalIcon />
      // </Button>
    }
  }
]