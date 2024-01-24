import { ReactNode } from "react";

type ExceptionBoundaryProps = {
  error?: string,
  children: ReactNode
  asChild?: boolean
}

const ExceptionBoundary = ({ error, asChild, children }: ExceptionBoundaryProps) => {
  if (error) {
      return (
        <>
          {asChild ? (
            children
          ) : (
            <div className="mt-2 ">
              <p className="text-sm font-bold text-red-500">{error}</p>
            </div>
          )}
        </>
      )
  }

  return children;
};

export { ExceptionBoundary };