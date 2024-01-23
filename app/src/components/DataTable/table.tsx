'use client'

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { ColumnDef, flexRender, getCoreRowModel, useReactTable } from "@tanstack/react-table";
import { DataTablePagination } from "./pagination";
import { ResponseType } from "@/types/ResponseType";

type DataTableProps<TData, TValue> = {
  columns: ColumnDef<TData, TValue>[]
  onRowClick?: (itemId: number) => void
  onPageChange: (page: number) => void
} & ResponseType<TData>

const DataTable = <TData, TValue>({ 
  data, 
  columns,
  onRowClick,
  onPageChange,
  ...paginationProps
}: DataTableProps<TData, TValue>) => {

  const table = useReactTable({
      columns,
      data,
      getCoreRowModel: getCoreRowModel(),
    })

  return (
    <>
      <Table className="w-full">
        <TableHeader>
            {table.getHeaderGroups().map((headerGroup) => (
              <TableRow key={headerGroup.id}>
                {headerGroup.headers.map((header) => {
                  return (
                    <TableHead key={header.id}>
                      {header.isPlaceholder
                        ? null
                        : flexRender(
                            header.column.columnDef.header,
                            header.getContext()
                          )}
                    </TableHead>
                  )
                })}
              </TableRow>
            ))}
          </TableHeader>

          <TableBody>
            {table.getRowModel().rows?.length ? (
              table.getRowModel().rows.map(row => (
                <TableRow key={row.id} onClick={() => {
                  onRowClick && onRowClick(Number(row.getValue('id')))
                }}>
                  {row.getVisibleCells().map(cell => (
                    <TableCell key={cell.id} className="cursor-pointer">
                      {flexRender(cell.column.columnDef.cell, cell.getContext())}
                    </TableCell>
                  ))}
                </TableRow>
              ))
            ) : (
              <TableRow>
                <TableCell colSpan={columns.length} className="h-24 text-center">
                  No results found.
                </TableCell>
              </TableRow>
            )}
          </TableBody>
      </Table>

      {table.getRowModel().rows?.length ? <DataTablePagination {...paginationProps} onPageChange={onPageChange} /> : null}
    </>
  );
};

export { DataTable };