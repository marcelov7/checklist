import React from 'react';

interface CardProps extends React.HTMLAttributes<HTMLDivElement> {
  children: React.ReactNode;
  variant?: 'default' | 'primary' | 'success' | 'warning' | 'error';
  size?: 'default' | 'compact' | 'elevated';
  hover?: boolean;
}

interface CardHeaderProps extends React.HTMLAttributes<HTMLDivElement> {
  children: React.ReactNode;
}

interface CardTitleProps extends React.HTMLAttributes<HTMLHeadingElement> {
  children: React.ReactNode;
  as?: 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6';
}

interface CardDescriptionProps extends React.HTMLAttributes<HTMLParagraphElement> {
  children: React.ReactNode;
}

interface CardContentProps extends React.HTMLAttributes<HTMLDivElement> {
  children: React.ReactNode;
}

interface CardFooterProps extends React.HTMLAttributes<HTMLDivElement> {
  children: React.ReactNode;
}

const Card: React.FC<CardProps> = ({ 
  children, 
  className = '', 
  variant = 'default',
  size = 'default',
  hover = true,
  ...props 
}) => {
  const getVariantClasses = () => {
    const baseClasses = 'bg-white border rounded-xl shadow-sm';
    
    switch (variant) {
      case 'primary':
        return `${baseClasses} border-blue-200 bg-gradient-to-br from-blue-50 to-white`;
      case 'success':
        return `${baseClasses} border-green-200 bg-gradient-to-br from-green-50 to-white`;
      case 'warning':
        return `${baseClasses} border-yellow-200 bg-gradient-to-br from-yellow-50 to-white`;
      case 'error':
        return `${baseClasses} border-red-200 bg-gradient-to-br from-red-50 to-white`;
      default:
        return `${baseClasses} border-gray-200`;
    }
  };

  const getSizeClasses = () => {
    switch (size) {
      case 'compact':
        return 'p-3';
      case 'elevated':
        return 'p-6 shadow-lg hover:shadow-xl';
      default:
        return 'p-4 shadow-md';
    }
  };

  const getHoverClasses = () => {
    if (hover) {
      return 'transition-all duration-200 hover:shadow-lg hover:-translate-y-1';
    }
    return 'transition-shadow duration-200';
  };

  const cardClasses = [
    getVariantClasses(),
    getSizeClasses(),
    getHoverClasses(),
    className
  ].filter(Boolean).join(' ');

  return (
    <div className={cardClasses} {...props}>
      {children}
    </div>
  );
};

export const CardHeader: React.FC<CardHeaderProps> = ({ children, className = '', ...props }) => {
  return (
    <div className={`flex flex-col space-y-1.5 p-6 pb-4 ${className}`} {...props}>
      {children}
    </div>
  );
};

export const CardTitle: React.FC<CardTitleProps> = ({ 
  children, 
  className = '', 
  as: Component = 'h3',
  ...props 
}) => {
  return (
    <Component className={`text-lg font-semibold leading-none tracking-tight text-gray-900 ${className}`} {...props}>
      {children}
    </Component>
  );
};

export const CardDescription: React.FC<CardDescriptionProps> = ({ children, className = '', ...props }) => {
  return (
    <p className={`text-sm text-gray-600 leading-relaxed ${className}`} {...props}>
      {children}
    </p>
  );
};

export const CardContent: React.FC<CardContentProps> = ({ children, className = '', ...props }) => {
  return (
    <div className={`p-6 pt-0 ${className}`} {...props}>
      {children}
    </div>
  );
};

export const CardFooter: React.FC<CardFooterProps> = ({ children, className = '', ...props }) => {
  return (
    <div className={`flex items-center p-6 pt-0 ${className}`} {...props}>
      {children}
    </div>
  );
};

export default Card;