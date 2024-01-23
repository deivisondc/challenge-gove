'use client'

import { SymbolIcon } from "@radix-ui/react-icons";
import { Button } from "../ui/button";
import { ColumnDef } from "@tanstack/react-table";
import { ResponseType } from "@/types/ResponseType";
import { DataTable } from "./table";
import { useState } from "react";
import { cn } from "@/lib/utils";

type TableProps<TData, TValue> = {
  columns: ColumnDef<TData, TValue>[]
  onRefresh: () => void
} & ResponseType<TData>

const Table = <TData, TValue>({ description, onRefresh, ...props }: TableProps<TData, TValue>) => {
  const [isRefreshing, setIsRefreshing] = useState(false)

  async function handleRefresh() {
    try {
      setIsRefreshing(true)
      await onRefresh()
    } finally {
      setIsRefreshing(false)
    }
  }

  return (
    <div className="my-4 ">
      <div className="flex justify-between items-end mb-1">
        <p className="text-sm text-gray-500">{description}</p>
        
        <Button
          variant="ghost"
          size="sm"
          onClick={handleRefresh}
          disabled={isRefreshing}
        >
          <SymbolIcon className={cn("mr-2", {
            'animate-spin spin-in-180': isRefreshing
          })} />

          {isRefreshing ? 'Refreshing' : 'Refresh'}
        </Button>
      </div>
      <div className="rounded-xl border p-4 w-full overflow-auto">
        <DataTable {...props} data={props.data} onPageChange={onRefresh} />
      </div>
    </div>
  );
};

export { Table };