import { Skeleton } from "../ui/skeleton";
import React from "react";

type TableSkeletonProps = {
  hasDescription?: boolean
  hasFilter?: boolean
  error?: string
}

const TableSkeleton = ({ hasDescription, hasFilter, error }: TableSkeletonProps) => {
  return (
    <div className="my-4">
      <div className="flex justify-between items-end mb-1">
        {error ? (
          <p className="text-sm font-bold text-red-500">{error}</p>
        ) : (
          hasDescription ? (
            <Skeleton className="h-3.5 w-[250px]"/>
          ) : (
            <Skeleton className="h-5 w-[250px]"/>
          )
        )}

        <Skeleton className="h-8 w-[100px]"/>
      </div>
      <div className="rounded-xl border p-4 w-full overflow-auto">
        {hasFilter && (
          <Skeleton className="h-8 w-[100px]"/>
        )}

        <div>
          <div className="flex gap-10 mt-4 mb-2">
            <Skeleton className="h-4 w-[100px] flex-1"/>
            <Skeleton className="h-4 w-[100px] flex-1 basis-[50%]"/>
            <Skeleton className="h-4 w-[100px]"/>
          </div>
          <hr />
          {new Array(10).fill(1).map((_, index) => (
            <React.Fragment key={index}>
              <div className="flex gap-10 my-2.5">
                <Skeleton className="h-4 w-[100px] flex-1"/>
                <Skeleton className="h-4 w-[100px] flex-1 basis-[50%]"/>
                <Skeleton className="h-4 w-[100px]"/>
              </div>
              {index !== 9 && <hr />}
            </React.Fragment>
          ))}
        </div>

        <div className="flex justify-between items-center mt-8">
          <Skeleton className="h-4 w-[200px]"/>

          <div className="flex gap-6 items-center">
            <Skeleton className="h-4 w-[100px]"/>

            <div className="flex gap-2">
              <Skeleton className="h-8 w-8"/>
              <Skeleton className="h-8 w-8"/>
              <Skeleton className="h-8 w-8"/>
              <Skeleton className="h-8 w-8"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export { TableSkeleton };