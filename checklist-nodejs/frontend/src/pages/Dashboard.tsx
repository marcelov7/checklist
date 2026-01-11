import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { 
  MapPin, 
  CheckSquare, 
  Users, 
  TrendingUp,
  Activity,
  Clock,
  AlertCircle
} from 'lucide-react';
import { Card, CardHeader, CardTitle, CardContent, Badge } from '../components/ui';
import { useAuthStore } from '../stores/authStore';
import { areaService } from '../services/areaService';

interface DashboardStats {
  totalAreas: number;
  areasAtivas: number;
  totalParadas: number;
  paradasCompletas: number;
  usuariosAtivos: number;
  progressoGeral: number;
}

export const Dashboard: React.FC = () => {
  const { user } = useAuthStore();
  const [stats, setStats] = useState<DashboardStats>({
    totalAreas: 0,
    areasAtivas: 0,
    totalParadas: 0,
    paradasCompletas: 0,
    usuariosAtivos: 0,
    progressoGeral: 0,
  });
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      setIsLoading(true);
      // Aqui você carregaria os dados reais da API
      // Por enquanto, vamos usar dados mockados
      const mockStats: DashboardStats = {
        totalAreas: 8,
        areasAtivas: 6,
        totalParadas: 45,
        paradasCompletas: 32,
        usuariosAtivos: 12,
        progressoGeral: 71,
      };
      
      setStats(mockStats);
    } catch (error) {
      console.error('Erro ao carregar dados do dashboard:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const statCards = [
    {
      title: 'Total de Áreas',
      value: stats.totalAreas,
      icon: MapPin,
      color: 'text-blue-600',
      bgColor: 'bg-blue-100',
      link: '/areas',
    },
    {
      title: 'Áreas Ativas',
      value: stats.areasAtivas,
      icon: Activity,
      color: 'text-green-600',
      bgColor: 'bg-green-100',
      link: '/areas?filter=active',
    },
    {
      title: 'Total de Paradas',
      value: stats.totalParadas,
      icon: CheckSquare,
      color: 'text-purple-600',
      bgColor: 'bg-purple-100',
      link: '/paradas',
    },
    {
      title: 'Paradas Completas',
      value: stats.paradasCompletas,
      icon: TrendingUp,
      color: 'text-emerald-600',
      bgColor: 'bg-emerald-100',
      link: '/paradas?filter=completed',
    },
  ];

  if (user?.role === 'ADMIN') {
    statCards.push({
      title: 'Usuários Ativos',
      value: stats.usuariosAtivos,
      icon: Users,
      color: 'text-orange-600',
      bgColor: 'bg-orange-100',
      link: '/users',
    });
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="md:flex md:items-center md:justify-between">
        <div className="flex-1 min-w-0">
          <h2 className="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Dashboard
          </h2>
          <p className="mt-1 text-sm text-gray-500">
            Bem-vindo de volta, {user?.nome}! Aqui está um resumo do sistema.
          </p>
        </div>
        <div className="mt-4 flex md:mt-0 md:ml-4">
          <Badge variant="secondary">
            {user?.role === 'ADMIN' ? 'Administrador' : 'Usuário'}
          </Badge>
        </div>
      </div>

      {/* Cards de estatísticas */}
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        {statCards.map((card) => {
          const Icon = card.icon;
          return (
            <Link key={card.title} to={card.link}>
              <Card className="stat-card" size="compact">
                <CardContent>
                  <div className="stat-card-header">
                    <div className={`stat-card-icon ${card.color}`}>
                      <Icon className="w-6 h-6" />
                    </div>
                  </div>
                  <div className="stat-card-content">
                    <h3 className="stat-card-title">{card.title}</h3>
                    <div className="stat-card-value">
                      {isLoading ? '...' : card.value}
                    </div>
                  </div>
                </CardContent>
              </Card>
            </Link>
          );
        })}
      </div>

      {/* Progresso Geral */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card variant="primary" size="elevated">
          <CardHeader>
            <CardTitle className="flex items-center">
              <TrendingUp className="w-5 h-5 mr-2" />
              Progresso Geral
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <span className="text-sm font-medium">
                  Conclusão das Paradas
                </span>
                <Badge variant="secondary">
                  {stats.paradasCompletas}/{stats.totalParadas}
                </Badge>
              </div>
              <div className="progress">
                <div
                  className="progress-bar progress-bar-success"
                  style={{ width: `${stats.progressoGeral}%` }}
                />
              </div>
              <p className="text-sm text-muted">
                {stats.progressoGeral}% das paradas foram concluídas
              </p>
            </div>
          </CardContent>
        </Card>

        <Card size="elevated">
          <CardHeader>
            <CardTitle className="flex items-center">
              <Clock className="w-5 h-5 mr-2" />
              Atividade Recente
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-3">
              <div className="activity-item">
                <div className="activity-indicator bg-success"></div>
                <div className="activity-content">
                  <p className="activity-text">
                    Parada "Verificação de Segurança" foi concluída
                  </p>
                  <p className="activity-time">2 horas atrás</p>
                </div>
              </div>
              
              <div className="activity-item">
                <div className="activity-indicator bg-primary"></div>
                <div className="activity-content">
                  <p className="activity-text">
                    Nova área "Produção B" foi criada
                  </p>
                  <p className="activity-time">5 horas atrás</p>
                </div>
              </div>
              
              <div className="activity-item">
                <div className="activity-indicator bg-warning"></div>
                <div className="activity-content">
                  <p className="activity-text">
                    Usuário João Silva foi atribuído à área Manutenção
                  </p>
                  <p className="activity-time">1 dia atrás</p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Ações Rápidas */}
      <Card>
        <CardHeader>
          <CardTitle>Ações Rápidas</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <Link to="/areas/new" className="action-card">
              <div className="action-card-icon text-primary">
                <MapPin className="w-8 h-8" />
              </div>
              <div className="action-card-content">
                <h4 className="action-card-title">Nova Área</h4>
                <p className="action-card-description">Criar nova área</p>
              </div>
            </Link>
            
            <Link to="/paradas/new" className="action-card">
              <div className="action-card-icon text-purple">
                <CheckSquare className="w-8 h-8" />
              </div>
              <div className="action-card-content">
                <h4 className="action-card-title">Nova Parada</h4>
                <p className="action-card-description">Criar nova parada</p>
              </div>
            </Link>
            
            {user?.role === 'ADMIN' && (
              <>
                <Link to="/users/new" className="action-card">
                  <div className="action-card-icon text-success">
                    <Users className="w-8 h-8" />
                  </div>
                  <div className="action-card-content">
                    <h4 className="action-card-title">Novo Usuário</h4>
                    <p className="action-card-description">Cadastrar usuário</p>
                  </div>
                </Link>
                
                <Link to="/settings" className="action-card">
                  <div className="action-card-icon text-warning">
                    <AlertCircle className="w-8 h-8" />
                  </div>
                  <div className="action-card-content">
                    <h4 className="action-card-title">Configurações</h4>
                    <p className="action-card-description">Gerenciar sistema</p>
                  </div>
                </Link>
              </>
            )}
          </div>
        </CardContent>
      </Card>
    </div>
  );
};