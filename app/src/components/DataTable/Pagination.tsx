import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";
import { ResponseType } from "@/types/ResponseType";
import { ChevronLeftIcon, ChevronRightIcon, DoubleArrowLeftIcon, DoubleArrowRightIcon } from "@radix-ui/react-icons";

type PaginationProps<T> = {
  onPageChange: (page: number) => void
} & Omit<ResponseType<T>, 'data'>

const Pagination = <T,>({ onPageChange, from, to, total, current_page, last_page, links }: PaginationProps<T>) => {
  const canGoPreviousPage = current_page >= 2
  const canGoNextPage = current_page <= last_page - 1

  const baseButtonClass = cn(
    'h-8 w-8 p-0 border-primary text-primary',
    'hover:bg-primary hover:text-primary-foreground'
  )

  function handlePageChange(label: string | number) {
    if (label === '...') {
      return
    }

    onPageChange(Number(label))
  }

  return (
    <div className="flex flex-col md:flex-row gap-2 items-center justify-between px-2 mt-4">
      <div className="flex-1 text-sm text-muted-foreground">
        Showing {from} to {to} out of {total}.
      </div>
      <div className="flex flex-col gap-2 md:flex-row items-center space-x-6 lg:space-x-8">
        <div className="flex items-center justify-center text-sm font-medium">
          {`Page ${current_page} of ${last_page}`}
        </div>
        <div className="flex items-center space-x-2">
          <Button
            variant="outline"
            className={cn(baseButtonClass, {
              'border-primary-muted-foreground bg-muted text-foreground': !canGoPreviousPage
            })}
            onClick={() => handlePageChange(current_page - 1)}
            disabled={!canGoPreviousPage}
          >
            <span className="sr-only">Go to previous page</span>
            <ChevronLeftIcon className="h-4 w-4" />
          </Button>
          {links.map((link, index) => (
            <Button
              key={link.label + index}
              variant="outline" 
              className={cn(baseButtonClass, {
                "text-primary hover:text-primary-foreground": !link.active,
                "font-bold bg-primary text-secondary hover:text-secondary": link.active
              })}
              onClick={() => handlePageChange(link.label)}
            >
              <span className="sr-only">Go to page {link.label}</span>
              {link.label}
            </Button>
          ))}
          <Button
            variant="outline"
            className={cn(baseButtonClass, {
              'border-primary-muted-foreground bg-muted text-foreground': !canGoNextPage
            })}
            onClick={() => handlePageChange(current_page + 1)}
            disabled={!canGoNextPage}
          >
            <span className="sr-only">Go to next page</span>
            <ChevronRightIcon className="h-4 w-4" />
          </Button>
        </div>
      </div>
    </div>
  );
};

export { Pagination };