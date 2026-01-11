import React from 'react';

interface BadgeProps extends React.HTMLAttributes<HTMLSpanElement> {
  variant?: 'default' | 'secondary' | 'success' | 'warning' | 'danger' | 'outline';
  size?: 'sm' | 'md' | 'lg';
  children: React.ReactNode;
}

const Badge: React.FC<BadgeProps> = ({
  variant = 'default',
  size = 'md',
  children,
  className = '',
  ...props
}) => {
  const getVariantClasses = () => {
    switch (variant) {
      case 'success':
        return 'bg-green-100 text-green-800 border border-green-200 hover:bg-green-200';
      case 'warning':
        return 'bg-yellow-100 text-yellow-800 border border-yellow-200 hover:bg-yellow-200';
      case 'danger':
        return 'bg-red-100 text-red-800 border border-red-200 hover:bg-red-200';
      case 'secondary':
        return 'bg-gray-100 text-gray-800 border border-gray-200 hover:bg-gray-200';
      case 'outline':
        return 'bg-transparent text-gray-700 border border-gray-300 hover:bg-gray-50';
      default:
        return 'bg-blue-100 text-blue-800 border border-blue-200 hover:bg-blue-200';
    }
  };

  const getSizeClasses = () => {
    switch (size) {
      case 'sm':
        return 'px-2 py-0.5 text-xs';
      case 'lg':
        return 'px-3 py-1 text-sm';
      default:
        return 'px-2.5 py-0.5 text-xs';
    }
  };

  const badgeClasses = [
    'inline-flex items-center rounded-full font-medium transition-colors duration-200',
    getVariantClasses(),
    getSizeClasses(),
    className
  ].filter(Boolean).join(' ');

  return (
    <span className={badgeClasses} {...props}>
      {children}
    </span>
  );
};

export { Badge };
export default Badge;