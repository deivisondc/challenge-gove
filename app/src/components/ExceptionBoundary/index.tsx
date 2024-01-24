import { ReactNode } from "react";

type ExceptionBoundaryProps = {
  error?: string,
  children: ReactNode
}

const ExceptionBoundary = ({ error, children }: ExceptionBoundaryProps) => {
  if (error) {
      return (
        <div className="my-4 ">
          <p className="text-sm font-bold text-red-500">{error}</p>
        </div>
      )
  }

  return children;
};

export { ExceptionBoundary };