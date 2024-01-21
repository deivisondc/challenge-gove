import { ReactNode } from "react";

interface PageTitleProps {
  children: ReactNode
}

const PageTitle = ({ children }: PageTitleProps) => {
  return (
    <h2 className="text-3xl font-bold tracking-tight">{children}</h2>
  );
};

export { PageTitle };