import React, { useState, useEffect } from 'react';
import { Save, X, Plus, Edit3 } from 'lucide-react';
import { 
  MobileForm, 
  MobileInput, 
  MobileSelect, 
  MobileTextarea, 
  MobileCheckbox,
  FormGroup, 
  FormActions 
} from './MobileForm';
import ResponsiveButton from '../ResponsiveButton';
import { useResponsive } from '../../hooks/useResponsive';

/**
 * Formulário genérico para criação/edição de entidades
 * Suporta diferentes tipos de campos e layouts responsivos
 */
export const EntityForm = ({
  title,
  fields = [],
  initialData = {},
  onSubmit,
  onCancel,
  loading = false,
  error = null,
  submitText = 'Salvar',
  cancelText = 'Cancelar',
  isEditing = false,
  className = '',
  ...props
}) => {
  const [formData, setFormData] = useState(initialData);
  const [errors, setErrors] = useState({});
  const { isMobile, getResponsiveFontSize } = useResponsive();

  useEffect(() => {
    setFormData(initialData);
  }, [initialData]);

  const validateField = (field, value) => {
    const rules = field.validation || {};
    
    if (rules.required && (!value || (typeof value === 'string' && !value.trim()))) {
      return `${field.label} é obrigatório`;
    }
    
    if (rules.minLength && value && value.length < rules.minLength) {
      return `${field.label} deve ter pelo menos ${rules.minLength} caracteres`;
    }
    
    if (rules.maxLength && value && value.length > rules.maxLength) {
      return `${field.label} deve ter no máximo ${rules.maxLength} caracteres`;
    }
    
    if (rules.pattern && value && !rules.pattern.test(value)) {
      return rules.message || `${field.label} tem formato inválido`;
    }
    
    if (rules.min && value && Number(value) < rules.min) {
      return `${field.label} deve ser pelo menos ${rules.min}`;
    }
    
    if (rules.max && value && Number(value) > rules.max) {
      return `${field.label} deve ser no máximo ${rules.max}`;
    }
    
    return null;
  };

  const validateForm = () => {
    const newErrors = {};
    
    fields.forEach(field => {
      const error = validateField(field, formData[field.name]);
      if (error) {
        newErrors[field.name] = error;
      }
    });
    
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }
    
    onSubmit?.(formData);
  };

  const handleInputChange = (fieldName, value) => {
    setFormData(prev => ({ ...prev, [fieldName]: value }));
    
    // Limpar erro do campo quando o usuário começar a digitar
    if (errors[fieldName]) {
      setErrors(prev => ({ ...prev, [fieldName]: '' }));
    }
  };

  const renderField = (field) => {
    const commonProps = {
      key: field.name,
      label: field.label,
      value: formData[field.name] || '',
      onChange: (e) => handleInputChange(field.name, e.target.value),
      error: errors[field.name],
      required: field.validation?.required,
      placeholder: field.placeholder,
      disabled: field.disabled || loading,
      ...field.props
    };

    switch (field.type) {
      case 'text':
      case 'email':
      case 'password':
      case 'number':
        return (
          <MobileInput
            {...commonProps}
            type={field.type}
            leftIcon={field.icon}
            autoComplete={field.autoComplete}
          />
        );

      case 'textarea':
        return (
          <MobileTextarea
            {...commonProps}
            rows={field.rows || 4}
          />
        );

      case 'select':
        return (
          <MobileSelect
            {...commonProps}
            options={field.options || []}
            placeholder={field.placeholder || 'Selecione uma opção'}
          />
        );

      case 'checkbox':
        return (
          <MobileCheckbox
            {...commonProps}
            checked={Boolean(formData[field.name])}
            onChange={(e) => handleInputChange(field.name, e.target.checked)}
          />
        );

      case 'custom':
        return field.render ? field.render(formData, handleInputChange, errors) : null;

      default:
        return (
          <MobileInput
            {...commonProps}
            type="text"
          />
        );
    }
  };

  const groupedFields = fields.reduce((groups, field) => {
    const groupName = field.group || 'default';
    if (!groups[groupName]) {
      groups[groupName] = [];
    }
    groups[groupName].push(field);
    return groups;
  }, {});

  return (
    <div className={`entity-form-container ${className}`} {...props}>
      {/* Header do formulário */}
      <div 
        className="entity-form-header"
        style={{
          display: 'flex',
          alignItems: 'center',
          gap: '0.75rem',
          marginBottom: '1.5rem',
          paddingBottom: '1rem',
          borderBottom: '1px solid var(--color-border)'
        }}
      >
        <div 
          className="form-icon"
          style={{
            width: isMobile ? '2.5rem' : '3rem',
            height: isMobile ? '2.5rem' : '3rem',
            backgroundColor: isEditing ? 'var(--color-warning)' : 'var(--color-primary)',
            borderRadius: '50%',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            color: 'white'
          }}
        >
          {isEditing ? <Edit3 size={isMobile ? 16 : 20} /> : <Plus size={isMobile ? 16 : 20} />}
        </div>
        
        <div>
          <h2 
            style={{
              fontSize: getResponsiveFontSize('xl'),
              fontWeight: 'bold',
              color: 'var(--color-text)',
              margin: 0
            }}
          >
            {title}
          </h2>
          <p 
            style={{
              fontSize: getResponsiveFontSize('sm'),
              color: 'var(--color-text-muted)',
              margin: '0.25rem 0 0 0'
            }}
          >
            {isEditing ? 'Edite as informações abaixo' : 'Preencha as informações abaixo'}
          </p>
        </div>
      </div>

      {/* Erro geral */}
      {error && (
        <div 
          className="entity-form-error"
          style={{
            backgroundColor: 'var(--color-danger-light)',
            border: '1px solid var(--color-danger)',
            color: 'var(--color-danger-dark)',
            padding: '0.75rem 1rem',
            borderRadius: 'var(--border-radius)',
            marginBottom: '1.5rem',
            fontSize: getResponsiveFontSize('sm')
          }}
        >
          {error}
        </div>
      )}

      {/* Formulário */}
      <MobileForm onSubmit={handleSubmit} spacing="normal">
        {Object.entries(groupedFields).map(([groupName, groupFields]) => (
          <div key={groupName} className="field-group">
            {groupName !== 'default' && (
              <h3 
                style={{
                  fontSize: getResponsiveFontSize('lg'),
                  fontWeight: '600',
                  color: 'var(--color-text)',
                  margin: '0 0 1rem 0',
                  paddingBottom: '0.5rem',
                  borderBottom: '1px solid var(--color-border-light)'
                }}
              >
                {groupName}
              </h3>
            )}
            
            <FormGroup 
              columns={groupFields.some(f => f.fullWidth === false) ? 2 : 1}
              spacing="normal"
            >
              {groupFields.map(renderField)}
            </FormGroup>
          </div>
        ))}

        <FormActions align="stretch">
          <ResponsiveButton
            type="submit"
            variant="primary"
            size={isMobile ? 'lg' : 'md'}
            loading={loading}
            disabled={loading}
            fullWidth={isMobile}
            leftIcon={<Save size={18} />}
          >
            {loading ? 'Salvando...' : submitText}
          </ResponsiveButton>
          
          <ResponsiveButton
            type="button"
            variant="outline"
            size={isMobile ? 'lg' : 'md'}
            onClick={onCancel}
            disabled={loading}
            fullWidth={isMobile}
            leftIcon={<X size={18} />}
          >
            {cancelText}
          </ResponsiveButton>
        </FormActions>
      </MobileForm>

      {/* Dicas para mobile */}
      {isMobile && fields.length > 3 && (
        <div 
          className="mobile-form-tips"
          style={{
            marginTop: '1.5rem',
            padding: '1rem',
            backgroundColor: 'var(--color-background-secondary)',
            borderRadius: 'var(--border-radius)',
            fontSize: getResponsiveFontSize('sm'),
            color: 'var(--color-text-muted)'
          }}
        >
          <p style={{ margin: '0 0 0.5rem 0', fontWeight: '500' }}>
            💡 Dicas para preenchimento:
          </p>
          <ul style={{ margin: 0, paddingLeft: '1.25rem' }}>
            <li>Use o teclado virtual para navegar entre campos</li>
            <li>Campos obrigatórios estão marcados com *</li>
            <li>Toque em "Salvar" quando terminar</li>
          </ul>
        </div>
      )}
    </div>
  );
};

/**
 * Configurações pré-definidas para formulários comuns
 */
export const FormConfigs = {
  area: {
    title: 'Área',
    fields: [
      {
        name: 'nome',
        type: 'text',
        label: 'Nome da Área',
        placeholder: 'Digite o nome da área',
        validation: { required: true, minLength: 2, maxLength: 100 },
        autoComplete: 'off'
      },
      {
        name: 'descricao',
        type: 'textarea',
        label: 'Descrição',
        placeholder: 'Descreva a área (opcional)',
        rows: 3,
        validation: { maxLength: 500 }
      }
    ]
  },

  parada: {
    title: 'Parada',
    fields: [
      {
        name: 'nome',
        type: 'text',
        label: 'Nome da Parada',
        placeholder: 'Digite o nome da parada',
        validation: { required: true, minLength: 2, maxLength: 100 },
        autoComplete: 'off'
      },
      {
        name: 'descricao',
        type: 'textarea',
        label: 'Descrição',
        placeholder: 'Descreva a parada (opcional)',
        rows: 3,
        validation: { maxLength: 500 }
      },
      {
        name: 'area_id',
        type: 'select',
        label: 'Área',
        placeholder: 'Selecione uma área',
        validation: { required: true },
        options: [] // Será preenchido dinamicamente
      },
      {
        name: 'ordem',
        type: 'number',
        label: 'Ordem',
        placeholder: '1',
        validation: { required: true, min: 1 },
        props: { min: 1 }
      },
      {
        name: 'ativa',
        type: 'checkbox',
        label: 'Parada ativa'
      }
    ]
  },

  user: {
    title: 'Usuário',
    fields: [
      {
        name: 'nome',
        type: 'text',
        label: 'Nome Completo',
        placeholder: 'Digite o nome completo',
        validation: { required: true, minLength: 2, maxLength: 100 },
        autoComplete: 'name'
      },
      {
        name: 'email',
        type: 'email',
        label: 'Email',
        placeholder: 'usuario@empresa.com',
        validation: { 
          required: true, 
          pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
          message: 'Email deve ter um formato válido'
        },
        autoComplete: 'email'
      },
      {
        name: 'password',
        type: 'password',
        label: 'Senha',
        placeholder: 'Digite a senha',
        validation: { required: true, minLength: 6 },
        autoComplete: 'new-password'
      },
      {
        name: 'role',
        type: 'select',
        label: 'Perfil',
        validation: { required: true },
        options: [
          { value: 'operador', label: 'Operador' },
          { value: 'supervisor', label: 'Supervisor' },
          { value: 'admin', label: 'Administrador' }
        ]
      },
      {
        name: 'ativo',
        type: 'checkbox',
        label: 'Usuário ativo'
      }
    ]
  }
};