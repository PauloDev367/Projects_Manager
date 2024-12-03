# Etapa 1: Criar a imagem com o SDK do .NET para compilar a aplicação
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build

# Definir o diretório de trabalho no container
WORKDIR /app

# Copiar os arquivos do projeto para dentro do container
COPY api/Api/. .

# Restaurar as dependências e construir a aplicação
RUN dotnet restore
RUN dotnet build -c Release -o /app/build

# Etapa 2: Publicar a aplicação para a produção
FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS base

# Definir o diretório de trabalho no container
WORKDIR /app

# Copiar a aplicação compilada para a imagem final
COPY --from=build /app/build .

# Expor a porta 80
EXPOSE 80

# Definir o comando para rodar a aplicação
ENTRYPOINT ["dotnet", "Api.dll"]