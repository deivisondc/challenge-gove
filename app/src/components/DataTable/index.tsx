'use client'

import { ColumnDef } from "@tanstack/react-table";
import { ResponseType } from "@/types/ResponseType";
import { DataTable } from "./table";

type TableProps<TData, TValue> = {
  columns: ColumnDef<TData, TValue>[]
} & ResponseType<TData>

const Table = <TData, TValue>({ ...props }: TableProps<TData, TValue>) => {
  return (
    <div className="my-4 ">
      <div className="rounded-xl border p-4 w-full overflow-auto">
        <DataTable {...props} data={props.data} />
      </div>
    </div>
  );
};

export { Table };