import { useEffect, useState } from 'react';
import { fetchRefreshToken } from '../api/wordpressApiClient';
import { getOrCreateBackgroundApp } from './backgroundAppUtils';
import { isRefreshTokenAvailable } from './isRefreshTokenAvailable';

export function useGetEmbedder() {
  const [embedder, setEmbedder] = useState<any>(null);

  useEffect(() => {
    if (isRefreshTokenAvailable()) {
      fetchRefreshToken().then(({ refreshToken }: { refreshToken: string }) => {
        setEmbedder(getOrCreateBackgroundApp(refreshToken));
      });
    }
  }, []);

  return embedder;
}
